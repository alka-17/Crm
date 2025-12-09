<x-app-layout>

    <div class="content">

        <div class="page-header">
            <h2>Update Contact</h2>
            <a href="{{ route('contacts.list') }}" class="btn-back">← Back to Contacts</a>
        </div>

        <div class="form-container">

            <form id="updateContactForm" enctype="multipart/form-data" method="post">
                @csrf
                <input type="hidden" name="contact_id" id="contact_id" value="{{ $contact->id }}">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $contact->name ?? '') }}" placeholder="Enter full name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $contact->email ?? '') }}" placeholder="Enter email address">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $contact->phone ?? '') }}" placeholder="Enter phone number">
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <label class="form-check-label me-2"><input type="radio" name="gender" value="male" {{ old('gender', $contact->gender ?? '') == 'male' ? 'checked' : '' }}> Male</label>
                    <label class="form-check-label me-2"><input type="radio" name="gender" value="female" {{ old('gender', $contact->gender ?? '') == 'female' ? 'checked' : '' }}> Female</label>
                    <label class="form-check-label me-2"><input type="radio" name="gender" value="other" {{ old('gender', $contact->gender ?? '') == 'other' ? 'checked' : '' }}> Other</label>
                </div>

                <div class="form-group">
                    <label>Profile Image</label>
                    @if(!empty($contact->profile_image))
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('storage/' . $contact->profile_image) }}" alt="Profile Image" style="max-width: 90px; height: auto; border:1px solid #ccc; padding:2px;">
                    </div>
                    @endif
                    <input type="file" name="profile_image" id="profile_image" class="form-control">
                </div>

                <div class="form-group">
                    <label>Additional File</label>
                    @if(!empty($contact->additional_file))
                    <div style="margin-bottom:10px;">
                        <a href="{{ asset('storage/' . $contact->additional_file) }}" target="_blank">
                            {{ basename($contact->additional_file) }}
                        </a>
                    </div>
                    @endif
                    <input type="file" class="form-control" name="additional_file">
                </div>


                <hr>
                <h6 class="mt-3">Custom Fields</h6>
                <div class="mt-2" id="customFieldsArea">
                    @if ($customFields->count() > 0)
                    @foreach($customFields as $field)
                    @php
                    $value = optional(
                    $contact->customValues->where('custom_field_id', $field->id)->first()
                    )->value;
                    @endphp

                    <div class="form-group">
                        <label>{{ $field->name }}</label>

                        @if(in_array($field->type, ['text','number','email','date']))
                        <input class="form-control" type="{{ $field->type }}" name="custom[{{ $field->id }}]" value="{{ $value }}">
                        @elseif($field->type == 'select')
                        <select class="form-control" name="custom[{{ $field->id }}]">
                            <option value="">Select</option>
                            @foreach($field->options as $opt)
                            <option value="{{ $opt }}" {{ $value == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @else
                        <input class="form-control" name="custom[{{ $field->id }}]" value="{{ $value }}">
                        @endif
                    </div>
                    @endforeach

                    @else
                    <p>No custom fields available.</p>
                    @endif
                </div>

                <button type="submit" id="updateContact" class="btn-submit">Update Contact</button>
                <!-- <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button> -->
            </form>

        </div>

    </div>
    <link rel="stylesheet" href="{{ asset('css/contacts/add-contact.css') }}">
    <script src="{{ asset('js/contact.js') }}"></script>
</x-app-layout>