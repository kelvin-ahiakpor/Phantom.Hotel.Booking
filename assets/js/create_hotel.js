document.addEventListener('DOMContentLoaded', function () {
    // Get DOM elements
    const elements = {
        form: document.getElementById('hotelForm'),
        profileBtn: document.getElementById('profileBtn'),
        profileModal: document.getElementById('profileModal'),
        closeProfileBtn: document.getElementById('closeProfileModal'),
        submitButton: document.querySelector('button[type="submit"]'),
        loadingSpinner: document.querySelector('.loading-spinner')
    };

    // Configuration
    const config = {
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedImageTypes: ['image/jpeg', 'image/png'],
        requiredFields: ['hotelName', 'hotelLocation', 'hotelDescription']
    };

    // Profile Modal Functionality
    if (elements.profileBtn && elements.profileModal && elements.closeProfileBtn) {
        elements.profileBtn.addEventListener('click', () => {
            elements.profileModal.classList.toggle('hidden');
        });

        elements.closeProfileBtn.addEventListener('click', () => {
            elements.profileModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (!elements.profileModal.contains(e.target) && !elements.profileBtn.contains(e.target)) {
                elements.profileModal.classList.add('hidden');
            }
        });
    }

    // Live Preview Functionality
    function initializeLivePreview() {
        const previewMappings = [
            { input: 'hotelName', preview: 'previewName', placeholder: 'Hotel Name' },
            { input: 'hotelLocation', preview: 'previewLocation', placeholder: 'Location' },
            { input: 'hotelDescription', preview: 'previewDescription', placeholder: 'Description will appear here...' }
        ];

        previewMappings.forEach(({ input, preview, placeholder }) => {
            const inputElement = document.getElementById(input);
            const previewElement = document.getElementById(preview);

            if (inputElement && previewElement) {
                // Set initial preview
                previewElement.textContent = inputElement.value || placeholder;

                // Update preview on input
                inputElement.addEventListener('input', () => {
                    previewElement.textContent = inputElement.value || placeholder;
                    hideError(`${input}Error`);
                });
            }
        });
    }

    // Image Upload Functionality
    function initializeImageUploads() {
        const imageInputs = [
            { input: 'hotelImage1', preview: 'preview1', livePreview: 'previewMainImage' },
            { input: 'hotelImage2', preview: 'preview2', livePreview: 'previewImage2' },
            { input: 'hotelImage3', preview: 'preview3', livePreview: 'previewImage3' }
        ];

        imageInputs.forEach(({ input, preview, livePreview }) => {
            setupImageInput(input, preview, livePreview);
        });
    }

    function setupImageInput(inputId, previewId, livePreviewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const livePreview = document.getElementById(livePreviewId);
        const wrapper = input.closest('.image-input-wrapper');
        const placeholder = wrapper.querySelector('.placeholder-text');

        wrapper.addEventListener('click', () => input.click());

        input.addEventListener('change', function () {
            handleImageSelection(this.files[0], preview, livePreview, placeholder);
        });

        // Drag and drop functionality
        wrapper.addEventListener('dragover', (e) => {
            e.preventDefault();
            wrapper.classList.add('border-blue-500');
        });

        wrapper.addEventListener('dragleave', () => {
            wrapper.classList.remove('border-blue-500');
        });

        wrapper.addEventListener('drop', (e) => {
            e.preventDefault();
            wrapper.classList.remove('border-blue-500');
            handleImageSelection(e.dataTransfer.files[0], preview, livePreview, placeholder);
        });
    }

    function handleImageSelection(file, preview, livePreview, placeholder) {
        if (!file) return;

        if (!config.allowedImageTypes.includes(file.type)) {
            showError('imageError', 'Please upload only JPG or PNG images');
            return;
        }

        if (file.size > config.maxFileSize) {
            showError('imageError', 'File size should not exceed 5MB');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            livePreview.src = e.target.result;
            livePreview.style.display = 'block';
            placeholder.style.display = 'none';
            hideError('imageError');
        };
        reader.readAsDataURL(file);
    }

    // Amenities Preview Functionality
    function initializeAmenities() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateAmenitiesPreview);
        });
        updateAmenitiesPreview(); // Initial update
    }

    function updateAmenitiesPreview() {
        const amenitiesContainer = document.querySelector('.preview-amenities');
        if (!amenitiesContainer) return;

        const selectedAmenities = document.querySelectorAll('input[type="checkbox"]:checked');
        amenitiesContainer.innerHTML = '';

        if (selectedAmenities.length > 0) {
            amenitiesContainer.innerHTML = '<h4 class="text-sm font-medium text-gray-700 mb-2">Amenities</h4>';
            const amenitiesGrid = document.createElement('div');
            amenitiesGrid.className = 'flex flex-wrap gap-2';

            selectedAmenities.forEach(checkbox => {
                const badge = createAmenityBadge(
                    checkbox.id,
                    checkbox.nextElementSibling.textContent.trim()
                );
                amenitiesGrid.appendChild(badge);
            });

            amenitiesContainer.appendChild(amenitiesGrid);
        }
    }

    function createAmenityBadge(amenityId, label) {
        const badge = document.createElement('span');
        badge.className = 'inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm';
        badge.textContent = label;
        return badge;
    }

    // Form Submission
    async function handleSubmit(e) {
        e.preventDefault();
        clearErrors();

        if (!validateForm()) return;

        try {
            elements.submitButton.disabled = true;
            elements.loadingSpinner.style.display = 'block';

            const formData = new FormData(elements.form);

            // Explicitly add amenities with proper values
            const amenities = ['wifi', 'pool', 'spa', 'restaurant', 'valet', 'concierge'];
            amenities.forEach(amenity => {
                const checkbox = document.getElementById(amenity);
                // Set value to '1' if checked, '0' if not
                formData.set(amenity, checkbox.checked ? '1' : '0');
            });

            // Add images if they exist
            ['hotelImage1', 'hotelImage2', 'hotelImage3'].forEach(imageId => {
                const input = document.getElementById(imageId);
                if (input.files[0]) {
                    formData.set(imageId, input.files[0]);
                }
            });

            const response = await fetch('../../actions/createHotel.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showNotification('Hotel created successfully!', 'success');
                setTimeout(() => window.location.href = data.redirect, 1500);
            } else {
                throw new Error(data.errors?.join('\n') || 'Failed to create hotel');
            }
        } catch (error) {
            showNotification(error.message, 'error');
        } finally {
            elements.submitButton.disabled = false;
            elements.loadingSpinner.style.display = 'none';
        }
    }

    // Validation and Error Handling
    function validateForm() {
        let isValid = true;

        config.requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                showError(`${field}Error`,
                    `${field.replace('hotel', '').replace(/([A-Z])/g, ' $1').trim()} is required`
                );
                isValid = false;
            }
        });

        const hasImage = ['hotelImage1', 'hotelImage2', 'hotelImage3'].some(id => {
            const input = document.getElementById(id);
            return input.files && input.files[0];
        });

        if (!hasImage) {
            showError('imageError', 'At least one hotel image is required');
            isValid = false;
        }

        return isValid;
    }

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
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Initialize all functionality
    function initialize() {
        initializeLivePreview();
        initializeImageUploads();
        initializeAmenities();
        elements.form.addEventListener('submit', handleSubmit);
    }

    // Start the application
    initialize();
});