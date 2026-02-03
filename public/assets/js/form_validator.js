/**
 * Utility function to validate forms using Bootstrap classes
 * @param {string|HTMLElement} formSelector - The form element or selector to validate
 * @returns {boolean} - True if valid, false otherwise
 */
function validateForm(formSelector) {
    const form = $(formSelector);
    let isValid = true;

    // Reset previous validation states
    form.find('.is-invalid').removeClass('is-invalid');
    form.find('.invalid-feedback').remove();

    // Validate inputs
    form.find('input, select, textarea').each(function() {
        if (!validateField(this)) {
            isValid = false;
        }
    });

    return isValid;
}

/**
 * Validates a single field
 * @param {HTMLElement} field - The input element to validate
 * @returns {boolean} - True if valid
 */
function validateField(field) {
    const input = $(field);
    let valid = true;
    let errorMessage = '';

    // Skip hidden fields or disabled fields
    if (input.is(':hidden') || input.is(':disabled')) {
        return true;
    }

    const val = input.val() ? input.val().trim() : '';

    // Required check
    if (input.prop('required') && val === '') {
        valid = false;
        errorMessage = 'Este campo es obligatorio.';
    }

    // Email check
    if (valid && input.attr('type') === 'email' && val !== '') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(val)) {
            valid = false;
            errorMessage = 'Ingrese un correo electrónico válido.';
        }
    }

    // Minlength check (if attribute exists)
    if (valid && input.attr('minlength') && val !== '') {
        const minLen = parseInt(input.attr('minlength'));
        if (val.length < minLen) {
            valid = false;
            errorMessage = `Debe tener al menos ${minLen} caracteres.`;
        }
    }
    
    // Exact length check (custom data attribute)
    if (valid && input.data('exact-length') && val !== '') {
        const exactLen = parseInt(input.data('exact-length'));
        if (val.length !== exactLen) {
             valid = false;
             errorMessage = `Debe tener exactamente ${exactLen} caracteres.`;
        }
    }

    // Show error if invalid
    if (!valid) {
        input.addClass('is-invalid');
        
        // Check if there is already a feedback element
        let feedback = input.siblings('.invalid-feedback');
        if (feedback.length === 0) {
            // If inside an input-group, we might need to place it differently, but for now append after
            if (input.parent('.input-group').length) {
                input.parent('.input-group').after(`<div class="invalid-feedback d-block">${errorMessage}</div>`);
            } else {
                 input.after(`<div class="invalid-feedback">${errorMessage}</div>`);
            }
        } else {
            feedback.text(errorMessage);
        }
    } else {
        input.addClass('is-valid');
        // Optionally remove is-valid after some time or just leave it
    }

    return valid;
}

// Attach input event listeners to clear errors as user types
$(document).on('input change', '.is-invalid', function() {
    $(this).removeClass('is-invalid');
    $(this).next('.invalid-feedback').remove();
    // Also handle input-group case
    if ($(this).parent('.input-group').length) {
        $(this).parent('.input-group').next('.invalid-feedback').remove();
    }
});

// Validate on blur (when user leaves the field)
$(document).on('blur', 'input, select, textarea', function() {
    // Only validate if field has been touched/modified or is of type email to be helpful
    if ($(this).val() !== '') {
        validateField(this);
    }
});
