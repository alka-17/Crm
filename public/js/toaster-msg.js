toastr.options = {
    closeButton: true,
    progressBar: true,
    timeOut: 5000,
    extendedTimeOut: 1000,
    positionClass: "toast-top-right",
};

if (typeof successMessage !== "undefined" && successMessage) {
    toastr.success(successMessage);
}

if (typeof errorMessage !== "undefined" && errorMessage) {
    toastr.error(errorMessage);
}

if (typeof warningMessage !== "undefined" && warningMessage) {
    toastr.warning(warningMessage);
}

if (typeof infoMessage !== "undefined" && infoMessage) {
    toastr.info(infoMessage);
}
