<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\CustomField;
use App\Models\CustomValue;
use App\Models\MergeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        try {
            $customFields = CustomField::all();
            return view('contacts.index', compact('customFields'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // store via AJAX
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:15',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'additional_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('contacts', 'public');
            $data['profile_image'] = $path;
        }
        if ($request->hasFile('additional_file')) {
            $path = $request->file('additional_file')->store('contacts', 'public');
            $data['additional_file'] = $path;
        }

        try {
            $contact = Contact::create($data);

            // save custom fields - expects custom[field_id] => value
            $custom = $request->input('custom', []);
            foreach ($custom as $fieldId => $val) {
                if (!is_null($val) && $val !== '') {
                    CustomValue::create([
                        'contact_id' => $contact->id,
                        'custom_field_id' => (int)$fieldId,
                        'value' => $val,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Contact created', 'contact' => $contact]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    //filtered contacts (returns partial)
    public function list(Request $request)
    {
        try {
            $q = Contact::with('mergeLogs')->where('is_active', true);
            // $q = Contact::where('is_active', true);

            if ($request->filled('name')) {
                $q->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('email')) {
                $q->where('email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('gender')) {
                $q->where('gender', $request->gender);
            }
            if ($request->filled('phone')) {
                $q->where('phone', $request->phone);
            }
            //filter by custom field (field_id and value)
            if ($request->filled('custom_value')) {
                $q->whereHas('customValues', function ($sub) use ($request) {
                    $sub->where('value', 'like', '%' . $request->custom_value . '%');
                });
            }
            $contacts = $q->orderBy('id', 'desc')->paginate(10);
            $genders = ['male', 'female', 'other'];
            $customValues = CustomValue::all();
            
            return view('contacts.partials.list', compact('contacts', 'genders', 'customValues'))->render();
        } catch (\Throwable $e) {
            // dd($e->getMessage(), $e->getLine(), $e->getFile());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
        }
    }

    // edit: returns contact + custom values
    public function edit(Contact $contact)
    {
        try {
            $contact->load('customValues.field');
            $customFields = CustomField::all();
            return view('contacts.partials.edit', compact('contact', 'customFields'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', '!Error: ' . $e->getMessage());
        }
    }

    // update via AJAX
    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|file|mimes:jpg,png,jpeg,gif|max:2048',
            'additional_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('contacts', 'public');
            $data['profile_image'] = $path;
            if ($contact->profile_image) Storage::disk('public')->delete($contact->profile_image);
        }
        if ($request->hasFile('additional_file')) {
            $path = $request->file('additional_file')->store('contacts', 'public');
            $data['additional_file'] = $path;
            if ($contact->additional_file) Storage::disk('public')->delete($contact->additional_file);
        }

        try {
            $contact->update($data);

            // update custom fields (upsert)
            $custom = $request->input('custom', []);
            foreach ($custom as $fieldId => $val) {
                $cv = CustomValue::firstOrNew(['contact_id' => $contact->id, 'custom_field_id' => $fieldId]);
                if ($val === null || $val === '') {
                    // remove if empty
                    if ($cv->exists) $cv->delete();
                } else {
                    $cv->value = $val;
                    $cv->save();
                }
            }

            return response()->json([
                'success' => true, 
                'message' => 'Contact updated', 
                'contact' => $contact
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // delete via AJAX (soft deactive + keep data)
    public function delete(Contact $contact)
    {
        try {
            if ($contact->customValues()->exists()) {
                $contact->customValues()->delete();
            }
            if($contact->mergeLogs()->exists()) {
                $contact->mergeLogs()->delete();
            }
            $contact->delete();
            return response()->json([
                'success' => true,
                'message' => 'Contact deleted',
                'contact' => $contact
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Merge: show Contact list in modal
    public function search(Request $request)
    {
        $query = $request->input('q');
        $excludeId = $request->input('exclude_id');

        $contacts = Contact::where('name', 'like', "%$query%")
            ->where('id', '!=', $excludeId)
            ->get(['id', 'name']);

        return response()->json($contacts);
    }

    public function mergePerform(Request $request)
    {
        $request->validate([
            'master_id' => 'required|exists:contacts,id',
            'secondary_id' => 'required|exists:contacts,id|different:master_id',
        ]);

        $master = Contact::findOrFail($request->master_id);
        $secondary = Contact::findOrFail($request->secondary_id);

        DB::beginTransaction();

        try {
            /*SNAPSHOT BEFORE MERGE (NO DATA LOSS)*/
            $master->load(['customValues']);
            $secondary->load(['customValues']);

            $mergedSnapshot = [
                'master_before' => $master->toArray(),
                'secondary_before' => $secondary->toArray(),
                'custom_values_master' => $master->customValues->map(function ($v) {
                    return $v->toArray();
                })->all(),
                'custom_values_secondary' => $secondary->customValues->map(function ($v) {
                    return $v->toArray();
                })->all(),
                'notes' => []
            ];

            /*MERGE EMAILS*/
            $extraEmails = is_array($master->extra_emails) ? $master->extra_emails : [];
            $extraEmails = array_filter($extraEmails, fn($e) => !empty($e));

            if (!empty($secondary->email) && $secondary->email !== $master->email && !in_array($secondary->email, $extraEmails)) {
                $extraEmails[] = $secondary->email;
            }

            if (is_array($secondary->extra_emails)) {
                foreach ($secondary->extra_emails as $email) {
                    if ($email && $email !== $master->email && !in_array($email, $extraEmails)) {
                        $extraEmails[] = $email;
                    }
                }
            }

            $master->extra_emails = array_values(array_unique($extraEmails));

            /*MERGE PHONES*/
            $extraPhones = is_array($master->extra_phones) ? $master->extra_phones : [];
            $extraPhones = array_filter($extraPhones, fn($p) => !empty($p));

            if (!empty($secondary->phone) && $secondary->phone !== $master->phone && !in_array($secondary->phone, $extraPhones)) {
                $extraPhones[] = $secondary->phone;
            }

            if (is_array($secondary->extra_phones)) {
                foreach ($secondary->extra_phones as $phone) {
                    if ($phone && $phone !== $master->phone && !in_array($phone, $extraPhones)) {
                        $extraPhones[] = $phone;
                    }
                }
            }

            $master->extra_phones = array_values(array_unique($extraPhones));

            /*MERGE PROFILE IMAGE / FILE (optional fallbacks)
         If master lacks a profile image but secondary has one,
         keep secondary's image on master (document didn't insist, optional)
        */
            if (empty($master->profile_image) && !empty($secondary->profile_image)) {
                $master->profile_image = $secondary->profile_image;
            }
            if (empty($master->additional_file) && !empty($secondary->additional_file)) {
                $master->additional_file = $secondary->additional_file;
            }

            /*MERGE CUSTOM FIELDS
         - Move a customValue row to master only when master doesn't already have a value.
         - If conflict (different values), keep master and store conflict in snapshot.
        */
            // Build master lookup: custom_field_id => value
            $masterValues = $master->customValues->pluck('value', 'custom_field_id')->toArray();

            // Use get() to avoid altering the relationship collection while iterating
            $secondaryCustoms = $secondary->customValues()->get();

            foreach ($secondaryCustoms as $sv) {
                $fieldId = $sv->custom_field_id;
                $secValue = $sv->value ?? null;
                $masterHas = array_key_exists($fieldId, $masterValues) && $masterValues[$fieldId] !== null && $masterValues[$fieldId] !== '';

                if (!$masterHas) {
                    // Move the custom value to master (update contact_id)
                    $sv->contact_id = $master->id;
                    $sv->save();
                } else {
                    // Conflict: keep master value, preserve secondary in snapshot
                    if ((string) $masterValues[$fieldId] !== (string) $secValue) {
                        $mergedSnapshot['notes'][] = [
                            'type' => 'custom_field_conflict',
                            'field_id' => $fieldId,
                            'master_value' => $masterValues[$fieldId],
                            'secondary_value' => $secValue,
                            'action' => 'kept_master'
                        ];
                    } else {
                        // identical values — we can safely delete or leave secondary's duplicate depending on policy.
                        // prefer to delete duplicate to avoid duplicates; store note.
                        $sv->delete();
                        $mergedSnapshot['notes'][] = [
                            'type' => 'custom_field_duplicate_removed',
                            'field_id' => $fieldId,
                            'value' => $secValue,
                            'action' => 'removed_duplicate'
                        ];
                    }
                }
            }

            /*MOVE NOTES OR OTHER RELATIONS (if present)
         - If your Contact model has a notes() relation, reassign those notes to master
         - This preserves history instead of deleting it.
        */
            if (method_exists($secondary, 'notes')) {
                $secondaryNotes = $secondary->notes()->get();
                foreach ($secondaryNotes as $note) {
                    // preserve original association in snapshot and then move
                    $mergedSnapshot['notes'][] = [
                        'type' => 'note_moved',
                        'note_id' => $note->id,
                        'original_contact_id' => $secondary->id,
                        'action' => 'moved_to_master'
                    ];
                    $note->contact_id = $master->id;
                    $note->save();
                }
            }

            /*FLAG SECONDARY AS MERGED (DO NOT DELETE)*/
            $secondary->merged_into = $master->id;
            $secondary->is_active = false;
            $secondary->save();

            /*SAVE MASTER AFTER UPDATES*/
            $master->save();

            /*LOG THE MERGE
         - Store the snapshot JSON for safe rollback/inspection
        */
            $logData = [
                'master_contact_id' => $master->id,
                'secondary_contact_id' => $secondary->id,
                // merged_data column should be JSON/text in DB
                'merged_data' => json_encode($mergedSnapshot),
                'notes' => !empty($mergedSnapshot['notes']) ? json_encode($mergedSnapshot['notes']) : null,
                'performed_by' => auth()->id(),
            ];

            MergeLog::create($logData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Contacts merged successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
