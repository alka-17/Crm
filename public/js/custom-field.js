let fieldIndex = 0;

document.getElementById("addFieldBtn").addEventListener("click", function () {
    fieldIndex++;
    const html = `
      <div class="field-row" id="field_${fieldIndex}">
        <button type="button" class="remove-btn" onclick="removeField(${fieldIndex})">X</button>
        <label>Column Name</label>
        <input type="text" name="fields[${fieldIndex}][name]" placeholder="Enter column name" required>
        
        <label>Input Type</label>
        <input type="text" name="fields[${fieldIndex}][type]" placeholder="Enter input type, e.g., text, number, date" required>

        <label>Options (JSON)</label>
        <input type="text" name="fields[${fieldIndex}][options]" placeholder='Enter options as JSON, e.g., ["A","B","C"]'>
      </div>
    `;
    document
        .getElementById("fieldsWrapper")
        .insertAdjacentHTML("beforeend", html);
});

function removeField(index) {
    const field = document.getElementById("field_" + index);
    if (field) field.remove();
}

// Handle form submission for custom fields
document
    .getElementById("customFieldsForm")
    .addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent normal form submission

        const form = new FormData(this);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "custom-fields/store",
            type: "POST",
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                toastr.success(response.message);
                location.reload();
            },
            error: function (xhr) {
                console.log(xhr);
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error("Something went wrong");
                }
            },
        });
    });
