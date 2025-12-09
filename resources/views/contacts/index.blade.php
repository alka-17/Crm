<x-app-layout>

  <div class="content">

    <div class="page-header">
      <h2>Add New Contact</h2>
      <a href="{{ route('contacts.list') }}" class="btn-back">← Back to Contacts</a>
    </div>

    <div class="form-container">

      <form id="contactForm" enctype="multipart/form-data" method="post">
        @csrf
        <input type="hidden" name="contact_id" id="contact_id">
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Enter full name" required>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address">
        </div>

        <div class="form-group">
          <label>Phone</label>
          <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone number">
        </div>

        <div class="form-group">
          <label>Gender</label>
          <label class="form-check-label me-2"><input type="radio" name="gender" value="male"> Male</label>
          <label class="form-check-label me-2"><input type="radio" name="gender" value="female"> Female</label>
          <label class="form-check-label me-2"><input type="radio" name="gender" value="other"> Other</label>
        </div>

        <div class="form-group">
          <label>Profile Image</label>
          <input type="file" name="profile_image" id="profile_image" class="form-control">
        </div>

        <div class="form-group">
          <label>Additional File</label>
          <input type="file" class="form-control" name="additional_file">
        </div>

        <hr>
        <h6 class="mt-3">Custom Fields</h6>
        <div class="mt-2" id="customFieldsArea">
          @if ($customFields &&$customFields->count() > 0)
            @foreach($customFields as $field)
            <div class="form-group">
              <label>{{ $field->name }}</label>

              @if(in_array($field->type, ['text','number','email','date']))
              <input class="form-control" type="{{ $field->type }}" name="custom[{{ $field->id }}]">
              @elseif($field->type == 'select')
              <select class="form-control" name="custom[{{ $field->id }}]">
                <option value="">Select</option>
                @foreach($field->options as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
              </select>
              @else
              <input class="form-control" name="custom[{{ $field->id }}]">
              @endif
            </div>
            @endforeach
          @else
          <p>No custom fields available.</p>
          @endif
        </div>

        <button type="submit" id="saveContact" class="btn-submit">Save Contact</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
      </form>

    </div>

  </div>
  <link rel="stylesheet" href="{{ asset('css/contacts/add-contact.css') }}">
  <script src="{{ asset('js/contact.js') }}"></script>
</x-app-layout>