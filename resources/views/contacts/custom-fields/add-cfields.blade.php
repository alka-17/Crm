<x-app-layout>

    <div class="container">
        <div class="page-header">
            <h3>Add Custom Fields</h3>
            <a href="{{ route('custom-fields.list') }}" class="btn-back">← Back to List</a>
        </div>
        <form id="customFieldsForm">
            <div id="fieldsWrapper">
                <div class="field-row" id="field_0">
                    <button type="button" class="remove-btn" onclick="removeField(0)">X</button>
                    <label>Column Name</label>
                    <input type="text" name="fields[0][name]" placeholder="Enter column name" required>

                    <label>Input Type</label>
                    <input type="text" name="fields[0][type]" placeholder="Enter input type, e.g., text, number, date" required>

                    <label>Options (JSON)</label>
                    <input type="text" name="fields[0][options]" placeholder='Enter options as JSON, e.g., ["A","B","C"]'>
                </div>
            </div>

            <button type="button" id="addFieldBtn">+ Add Field</button>
            <button type="submit" id="saveFieldsBtn" class="submit-btn">Save Fields</button>
        </form>
    </div>

    <link rel="stylesheet" href="{{ asset('css/custom-fields/add.css') }}">

    <script src="{{ asset('js/custom-field.js') }}"></script>
</x-app-layout>