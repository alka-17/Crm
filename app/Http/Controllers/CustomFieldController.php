<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index()
    {
        return view('contacts.custom-fields.add-cfields');
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'fields' => 'required|array',
                'fields.*.name' => 'required|string',
                'fields.*.type' => 'required|string',
                'fields.*.options' => 'nullable|string',
            ]);
    
            foreach ($request->fields as $field) {
                CustomField::create([
                    'name' => $field['name'],
                    'type' => $field['type'],
                    'options' => (!empty($field['options']))
                        ? json_decode($field['options'], true)
                        : null,
                ]);
            }
            // Return JSON for AJAX
            return response()->json([
                'status' => 'success',
                'message' => 'Custom fields saved!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save custom fields: ' . $e->getMessage(),
            ]);
        }
    }

    public function list() {
        try {
            $customFields = CustomField::paginate(10);
            // dd($customFields);
            return view('contacts.custom-fields.list-cfields', compact('customFields'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request, CustomField $customField) {
        try {
            $request->validate([
                'name' => 'required|string',
                'type' => 'required|string',
                'options' => 'nullable|string',
            ]);
    
            $customField->update([
                'name' => $request->name,
                'type' => $request->type,
                'options' => (!empty($request->options))
                    ? json_decode($request->options, true)
                    : null,
            ]);
    
            return redirect()->back()->with('success', 'Custom field updated successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete(Request $request, CustomField $customField) {
        try {
            $customField->delete();
            return redirect()->back()->with('success', 'Custom field deleted successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, CustomField $customField) {
        try {
            $customField->delete();
            return redirect()->back()->with('success', 'Custom field deleted successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
