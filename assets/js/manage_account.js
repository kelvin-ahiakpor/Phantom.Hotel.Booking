import checkInternetConnection from '../../utils/checkInternetConnection.js';

function handleOffline() {
    alert("No internet connection. Redirecting to offline page...");
    window.location.href = "/no_internet.html"; 
}

function handleOnline() {
    console.log("Back online!");
}

checkInternetConnection(handleOffline, handleOnline);

document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const accountForm = document.getElementById('accountForm');
    const deleteModal = document.getElementById('deleteModal');
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteAccountForm = document.getElementById('deleteAccountForm');
    const loadingSpinner = document.querySelector('.loading-spinner');

    // Configuration
    const config = {
        requiredFields: ['firstName', 'lastName', 'email'],
        emailRegex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        phoneRegex: /^\+?[\d\s-]{10,}$/,
        minPasswordLength: 8
    };

    // Delete Account Modal Handlers
    function initializeDeleteModal() {
        deleteAccountBtn.addEventListener('click', () => toggleModal(true));
        cancelDeleteBtn.addEventListener('click', () => toggleModal(false));

        // Close modal when clicking outside
        deleteModal.querySelector('.modal-backdrop').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                toggleModal(false);
            }
        });

        // Handle delete account form submission
        deleteAccountForm.addEventListener('submit', handleDeleteAccount);
    }

    function toggleModal(show) {
        if (show) {
            deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            deleteModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Clear password field and errors
            document.getElementById('deletePassword').value = '';
            hideError('deletePasswordError');
        }
    }

    // Form Validation
    function validateForm() {
        let isValid = true;

        // Required fields
        config.requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                showError(`${field}Error`, `${field.replace(/([A-Z])/g, ' $1').trim()} is required`);
                isValid = false;
            }
        });

        // Email validation
        const email = document.getElementById('email');
        if (!config.emailRegex.test(email.value.trim())) {
            showError('emailError', 'Please enter a valid email address');
            isValid = false;
        }

        // Phone validation (if provided)
        const phone = document.getElementById('phoneNumber');
        if (phone.value.trim() && !config.phoneRegex.test(phone.value.trim())) {
            showError('phoneError', 'Please enter a valid phone number');
            isValid = false;
        }

        // Password validation (if changing password)
        const currentPassword = document.getElementById('currentPassword');
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        if (currentPassword.value || newPassword.value || confirmPassword.value) {
            if (!currentPassword.value) {
                showError('currentPasswordError', 'Current password is required');
                isValid = false;
            }
            if (!newPassword.value) {
                showError('newPasswordError', 'New password is required');
                isValid = false;
            }
            if (newPassword.value !== confirmPassword.value) {
                showError('confirmPasswordError', 'Passwords do not match');
                isValid = false;
            }
            if (newPassword.value.length < config.minPasswordLength) {
                showError('newPasswordError', 'Password must be at least 8 characters');
                isValid = false;
            }
        }

        return isValid;
    }

    // Account Update Handler
    async function handleAccountUpdate(e) {
        e.preventDefault();
        clearErrors();

        if (!validateForm()) {
            return;
        }

        const submitButton = accountForm.querySelector('button[type="submit"]');
        const loadingSpinner = submitButton.querySelector('.loading-spinner');

        try {
            submitButton.disabled = true;
            loadingSpinner.style.display = 'block';

            const formData = new FormData(accountForm);
            const response = await fetch('../../actions/update_account.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showNotification('Account updated successfully!', 'success');
                if (formData.get('newPassword')) {
                    // Clear password fields after successful update
                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                }
            } else {
                throw new Error(data.errors?.join('\n') || 'Failed to update account');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message, 'error');
        } finally {
            submitButton.disabled = false;
            loadingSpinner.style.display = 'none';
        }
    }

    // Delete Account Handler
    async function handleDeleteAccount(e) {
        e.preventDefault();
        clearErrors();

        const password = document.getElementById('deletePassword');
        if (!password.value) {
            showError('deletePasswordError', 'Please enter your password');
            return;
        }

        const submitButton = deleteAccountForm.querySelector('button[type="submit"]');
        const loadingSpinner = submitButton.querySelector('.loading-spinner');

        try {
            submitButton.disabled = true;
            loadingSpinner.classList.remove('hidden');

            const formData = new FormData();
            formData.append('password', password.value);
            formData.append('userId', document.querySelector('input[name="userId"]').value);

            const response = await fetch('../../actions/delete_account.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showNotification('Account deleted successfully', 'success');
                setTimeout(() => {
                    window.location.href = '../../actions/logout.php';
                }, 1500);
            } else {
                throw new Error(data.errors?.join('\n') || 'Failed to delete account');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message, 'error');
        } finally {
            submitButton.disabled = false;
            loadingSpinner.classList.add('hidden');
        }
    }

    // Error Handling
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    }

    function hideError(elementId) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    function clearErrors() {
        document.querySelectorAll('.error-text').forEach(error => {
            error.style.display = 'none';
        });
    }

    // Notifications
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 
            ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}
            transform transition-all duration-300 ease-in-out`;

        notification.textContent = message;
        document.body.appendChild(notification);

        // Slide in
        requestAnimationFrame(() => {
            notification.style.transform = 'translateY(20px)';
        });

        // Slide out and remove
        setTimeout(() => {
            notification.style.transform = 'translateY(-100%)';
            setTimeout(() => notification.remove(), 300);
        }, 4700);
    }

    // Input Event Listeners
    function setupInputListeners() {
        // Clear field-specific errors on input
        config.requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('input', () => hideError(`${field}Error`));
            }
        });

        // Clear password errors on input
        ['currentPassword', 'newPassword', 'confirmPassword'].forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('input', () => hideError(`${field}Error`));
            }
        });

        // Clear phone error on input
        const phoneInput = document.getElementById('phoneNumber');
        if (phoneInput) {
            phoneInput.addEventListener('input', () => hideError('phoneError'));
        }
    }

    // Initialize everything
    function initialize() {
        initializeDeleteModal();
        setupInputListeners();
        accountForm.addEventListener('submit', handleAccountUpdate);
    }

    // Start the application
    initialize();
});