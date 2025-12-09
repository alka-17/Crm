<x-app-layout>
  <div class="content">

    <div class="card-header">
      <h3>Contacts</h3>
      <a href="{{ route('contacts.index') }}" class="btn-plus"><i class="fa fa-plus"></i> Add New</a>
    </div>
    <!-- FILTER FORM -->
    <form method="GET" action="{{ route('contacts.list') }}" class="filter-box">

      <input type="text" name="name" placeholder="Search Name"
        value="{{ request('name') }}">

      <input type="text" name="email" placeholder="Search Email"
        value="{{ request('email') }}">

      <input type="text" name="phone" placeholder="Search Phone"
        value="{{ request('phone') }}">

      <select name="gender">
        <option value="">All Genders</option>
        <option value="male" {{ request('gender')=='male' ? 'selected' : '' }}>Male</option>
        <option value="female" {{ request('gender')=='female' ? 'selected' : '' }}>Female</option>
        <option value="other" {{ request('gender')=='other' ? 'selected' : '' }}>Other</option>
      </select>

      <select name="custom_value">
        <option value="">All Custom Values</option>
        @foreach($customValues as $value)
        <option value="{{ $value->value }}" {{ request('custom_value')==$value->value ? 'selected' : '' }}>{{ $value->value }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="{{ route('contacts.list') }}" class="btn btn-secondary">Reset</a>

    </form>

    <!-- CONTACT LIST TABLE -->
    <div class="table-box">
      <table>
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Merged Contacts</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          @if($contacts->count() > 0)
          @foreach($contacts as $contact)
          <tr data-id="{{ $contact->id }}">
            <td>{{ $contact->id }}</td>
            <td>{{ $contact->name }}</td>
            <td>{{ $contact->email }}</td>
            <td>{{ $contact->phone }}</td>
            <td>{{ $contact->gender }}</td>
            <td>
              @php
              $count = $contact->mergeLogs->count();
              @endphp

              @if($count > 0)
              <button class="btn btn-sm btn-info toggle-merged" data-id="{{ $contact->id }}"> View Merged ({{ $count }})
              </button>
              @else
              <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              <button class="edit btn-edit" data-id="{{ $contact->id }}"><a href="{{ route('contacts.edit', $contact->id) }}"><i class="fa fa-edit"></i></a></button>
              <button class="delete btn-delete" data-id="{{ $contact->id }}"><i class="fa fa-trash"></i></button>
              @if($count == 0)
              <button class="btn btn-sm btn-warning btn-merge" data-id="{{ $contact->id }}">Merge</button>
              @endif
            </td>
          </tr>
          {{-- ADD THIS DIRECTLY AFTER THE ROW --}}
          <tr class="merged-row merged-{{ $contact->id }}" style="display:none; background:#f9f9f9;">
            <td colspan="7">
              @if ($contact->mergeLogs->count() > 0)
              @foreach($contact->mergeLogs as $log)
              @php
              // Decode JSON safely
              $data = json_decode($log->merged_data, true) ?? [];
              @endphp

              <div class="merged-box" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; padding:10px; border:1px solid #ddd; margin-bottom:10px; border-radius:6px;">
                <!-- Main Contact -->
                <div>
                  <strong>Main Contact details:</strong>
                  <ul>
                    <li><b>Name:</b> {{ $data['master_before']['name'] ?? '-' }}</li>
                    <li><b>Email:</b> {{ $data['master_before']['email'] ?? '-' }}</li>
                    <li><b>Phone:</b> {{ $data['master_before']['phone'] ?? '-' }}</li>
                    <li><b>Gender:</b> {{ $data['master_before']['gender'] ?? '-' }}</li>
                  </ul>
                  <strong>Custom Fields (Main Contact):</strong>
                  <ul>
                    @foreach($data['custom_values_master'] ?? [] as $cv)
                    <li>Field {{ $cv['custom_field_id'] ?? '-' }}: {{ $cv['value'] ?? '-' }}</li>
                    @endforeach
                  </ul>
                </div>

                <!-- Secondary Contact -->
                <div>
                  <strong>Secondary Contact details:</strong>
                  <ul>
                    <li><b>Name:</b> {{ $data['secondary_before']['name'] ?? '-' }}</li>
                    <li><b>Email:</b> {{ $data['secondary_before']['email'] ?? '-' }}</li>
                    <li><b>Phone:</b> {{ $data['secondary_before']['phone'] ?? '-' }}</li>
                    <li><b>Gender:</b> {{ $data['secondary_before']['gender'] ?? '-' }}</li>
                  </ul>
                  <strong>Custom Fields (Secondary Contact):</strong>
                  <ul>
                    @foreach($data['custom_values_secondary'] ?? [] as $cv)
                    <li>Field {{ $cv['custom_field_id'] ?? '-' }}: {{ $cv['value'] ?? '-' }}</li>
                    @endforeach
                  </ul>
                </div>

                <!-- Merged / Notes -->
                <div>
                  <strong>Merged Contact details:</strong>
                  <ul>
                    @foreach($data['notes'] ?? [] as $note)
                    @if(isset($note['field_id']) || isset($note['value']))
                    <li>@isset($note['field_id'])<b>Field ID:</b> {{ $note['field_id'] }}@endisset</li>
                     <li>@isset($note['master_value'])<b>Master Value:</b> {{ $note['master_value'] }}@endisset</li>
                     <li>@isset($note['secondary_value'])<b>Secondary Value:</b> {{ $note['secondary_value'] }}@endisset</li>
                     <li>@isset($note['value'])<b>Value:</b> {{ $note['value'] }}@endisset</li>
                     <li>@isset($note['action'])<b>Action:</b> {{ $note['action'] }}@endisset</li>
                    @endif
                    @endforeach
                  </ul>
                </div>
              </div>

              @endforeach
              @endif
            </td>
          </tr>


          <!-- === -->
          @endforeach
          @else
          <tr>
            <td colspan="6" class="text-center">No contacts found</td>
          </tr>
          @endif
        </tbody>
      </table>
      <div class="mt-4">
        {{ $contacts->links() }}
      </div>
    </div>
  </div>
  <script>
    $(document).on("click", ".toggle-merged", function() {
      let id = $(this).data("id");
      $(".merged-" + id).toggle();
    });
  </script>
  <script src="{{ asset('js/contact.js') }}"></script>
  @include('contacts.partials.modals')
</x-app-layout>