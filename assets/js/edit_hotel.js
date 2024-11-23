document.addEventListener('DOMContentLoaded', function () {
    // Configuration
    const config = {
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedImageTypes: ['image/jpeg', 'image/png', 'image/jpg'],
        requiredFields: ['hotelName', 'location', 'address', 'description']
    };

    // DOM Elements
    const form = document.getElementById('editHotelForm');
    const loadingSpinner = document.querySelector('.loading-spinner');
    const profileBtn = document.getElementById('profileBtn');
    const profileModal = document.getElementById('profileModal');
    const closeProfileModal = document.getElementById('closeProfileModal');

    // Profile Modal Handlers
    function initializeProfileModal() {
        profileBtn.addEventListener('click', () => {
            profileModal.classList.toggle('hidden');
        });

        closeProfileModal.addEventListener('click', () => {
            profileModal.classList.add('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!profileModal.contains(e.target) && !profileBtn.contains(e.target)) {
                profileModal.classList.add('hidden');
            }
        });
    }

    // Image Upload Handlers
    function setupImageInput(index) {
        const input = document.getElementById(`hotelImage${index}`);
        const preview = document.getElementById(`preview${index}`);
        const wrapper = input.closest('.image-input-wrapper');
        const placeholder = wrapper.querySelector('.placeholder-text');

        wrapper.addEventListener('click', () => input.click());
        wrapper.addEventListener('dragover', handleDragOver);
        wrapper.addEventListener('dragleave', handleDragLeave);
        wrapper.addEventListener('drop', (e) => handleDrop(e, input, preview, placeholder));

        input.addEventListener('change', function () {
            handleImageSelection(this, preview, placeholder);
        });
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.currentTarget.classList.add('border-blue-500');
    }

    function handleDragLeave(e) {
        e.currentTarget.classList.remove('border-blue-500');
    }

    function handleDrop(e, input, preview, placeholder) {
        e.preventDefault();
        e.currentTarget.classList.remove('border-blue-500');

        const file = e.dataTransfer.files[0];
        if (file) {
            input.files = e.dataTransfer.files;
            handleImageSelection(input, preview, placeholder);
        }
    }

    function handleImageSelection(input, preview, placeholder) {
        const file = input.files[0];
        if (!file) return;

        // Validate file type
        if (!config.allowedImageTypes.includes(file.type)) {
            showError('imageError', 'Please upload only JPG or PNG images');
            return;
        }

        // Validate file size
        if (file.size > config.maxFileSize) {
            showError('imageError', 'File size should not exceed 5MB');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
        hideError('imageError');
    }

    // Form Validation
    function validateForm() {
        let isValid = true;

        // Check required fields
        config.requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                showError(
                    `${field}Error`,
                    `${field.replace(/([A-Z])/g, ' $1').trim()} is required`
                );
                isValid = false;
            }
        });

        return isValid;
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

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 px-6 py-3 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }

    // Form Submission Handler
    async function handleFormSubmit(e) {
        e.preventDefault();
        clearErrors();

        if (!validateForm()) {
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        loadingSpinner.style.display = 'block';

        try {
            const formData = new FormData(form);
            formData.append('availability', document.getElementById('availability').checked ? '1' : '0');

            const response = await fetch('../../actions/updateHotel.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showNotification('Hotel updated successfully!');
                setTimeout(() => {
                    window.location.href = 'manage_hotel.php';
                }, 1500);
            } else {
                const errorMessage = Array.isArray(data.errors)
                    ? data.errors.join('\n')
                    : 'Failed to update hotel';
                throw new Error(errorMessage);
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message, 'error');
        } finally {
            submitButton.disabled = false;
            loadingSpinner.style.display = 'none';
        }
    }

    // Initialize everything
    function initialize() {
        initializeProfileModal();

        // Setup image inputs
        for (let i = 1; i <= 3; i++) {
            setupImageInput(i);
        }

        // Setup form submission
        form.addEventListener('submit', handleFormSubmit);

        // Add input event listeners to clear errors
        config.requiredFields.forEach(field => {
            const input = document.getElementById(field);
            input.addEventListener('input', () => hideError(`${field}Error`));
        });
    }

    // Start the application
    initialize();
});