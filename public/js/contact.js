// save contact
let contactForm = document.querySelector("#contactForm");
if (!!contactForm) {
    contactForm.addEventListener("submit", function (e) {
        e.preventDefault();

        jQuery.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "contacts/store",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    // alert(response.message);
                    toastr.success(response.message);
                    window.location.reload();
                }
            },
            error: function (xhr) {
                // alert(xhr.responseJSON.message);
                toastr.error(xhr.responseJSON.message);
            },
        });
    });
}
// update contact
let updateContactForm = document.querySelector("#updateContactForm");
if (updateContactForm) {
    updateContactForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const contactId = document.querySelector("#contact_id").value;

        jQuery.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },

            url: "/contacts/update/" + contactId,
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,

            success: function (response) {
                toastr.success(response.message);
                // Redirect after 1 second
                setTimeout(() => {
                    window.location.href = "/contacts/list";
                }, 1000);
            },

            error: function (xhr) {
                toastr.error(xhr.responseJSON.message);
            },
        });
    });
}

// Merge contact list
$(document).on("click", ".btn-merge", function () {
    let secondaryId = $(this).data("id");
    $("#secondary_id").val(secondaryId);

    $("#mergeModal").modal("show");

    // Delay initialization to ensure modal DOM is visible
    setTimeout(function () {
        $("#master_id").select2({
            dropdownParent: $("#mergeModal"),
            placeholder: "Type to search contact...",
            minimumInputLength: 2,
            ajax: {
                url: "/contacts/search",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        exclude_id: secondaryId,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (contact) {
                            return {
                                id: contact.id,
                                text: "#" + contact.id + " " + contact.name,
                            };
                        }),
                    };
                },
                cache: true,
            },
        });

        // Clear previous selection
        $("#master_id").val(null).trigger("change");
    }, 200);
});

// confirm merge
$(document).on("click", "#confirmMerge", function () {
    console.log("hii");

    let master = $("#master_id").val();
    let secondary = $("#secondary_id").val();
    console.log(master, secondary);
    if (!master) {
        alert("Please select a master contact.");
        return;
    }

    if (!secondary) {
        alert("Missing secondary contact ID.");
        return;
    }

    if (!confirm("Are you sure you want to merge?")) return;

    $.post("/contacts/merge", $("#mergeForm").serialize(), function (res) {
        if (res.success) {
            // alert(res.message);
            toastr.success(res.message);
            $("#mergeModal").modal("hide");
            location.reload();
        } else {
            // alert(res.message);
            toastr.error(res.message);
        }
    }).fail(function (xhr) {
        toastr.error("Merge failed: " + (xhr.responseJSON?.message || "error"));
    });
});


// Delete Contact
$(document).on("click", ".btn-delete", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to deactivate this contact?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, deactivate",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform AJAX delete
            $.ajax({
                url: "/contacts/delete/" + id,
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },

                success: function (res) {
                    toastr.success("Contact deactivated successfully");
                    location.reload();
                },

                error: function (xhr) {
                    toastr.error(
                        "Delete failed: " +
                            (xhr.responseJSON?.message || "error")
                    );
                },
            });
        }
    });
});
