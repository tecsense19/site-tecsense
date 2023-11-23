// swal-common.js

// Common Swal configuration
const SwalConfig = {
    icon: 'info',
    confirmButtonColor: '#031e23',
    cancelButtonColor: '#d33',
    allowOutsideClick: false,
    allowEscapeKey: false,
};

const SwalConfirm = {
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    confirmButtonColor: '#031e23',
    cancelButtonText: 'No',
    cancelButtonColor: '#d33',
    allowOutsideClick: false,
    allowEscapeKey: false
}

// Function to show a success message
function showSuccessMessage(title, text, callback) {
    Swal.fire({ ...SwalConfig, icon: 'success', title, text }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
}

// Function to show an error message
function showErrorMessage(title, text) {
    Swal.fire({ ...SwalConfig, icon: 'error', title, text });
}

// Function to show an confirmation message
function showDeleteConfirmation(title, text, callback) {
    Swal.fire({ ...SwalConfirm, icon: 'warning', title, text }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
}