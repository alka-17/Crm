<x-app-layout>
    <div class="content">
        <div class="card-header">
            <h3>Custom Fields List</h3>
            <a href="{{ route('custom-fields.index') }}" class="btn-plus"><i class="fa fa-plus"></i> Add Custom Fields</a>
        </div>
        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Options</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customFields as $field)
                    <tr>
                        <td>{{ $field->id }}</td>
                        <td>{{ $field->name }}</td>
                        <td>{{ $field->type }}</td>
                        <td>
                            @if ($field->options)
                            <ul>
                                @foreach ($field->options as $option)
                                <li>{{ $option }}</li>
                                @endforeach
                            </ul>
                            @endif
                        </td>
                        <td>
                            <button class="edit btn-edit" data-id="{{ $field->id }}"><a href="{{ route('custom-fields.edit', $field->id) }}"><i class="fa fa-edit"></i></a></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $customFields->links() }}
            </div>
        </div>
    </div>
</x-app-layout>