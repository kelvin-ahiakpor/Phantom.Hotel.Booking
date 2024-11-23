document.addEventListener('DOMContentLoaded', function () {
    // Form elements
    const hotelForm = document.getElementById('hotelForm');
    const submitButton = hotelForm.querySelector('button[type="submit"]');
    const loadingSpinner = document.querySelector('.loading-spinner');

    // Profile modal elements
    const profileBtn = document.getElementById('profileBtn');
    const profileModal = document.getElementById('profileModal');
    const closeProfileModal = document.getElementById('closeProfileModal');

    // Profile modal handlers
    if (profileBtn && profileModal && closeProfileModal) {
        profileBtn.addEventListener('click', () => {
            profileModal.classList.toggle('hidden');
        });

        closeProfileModal.addEventListener('click', () => {
            profileModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileModal.contains(e.target) && !profileBtn.contains(e.target)) {
                profileModal.classList.add('hidden');
            }
        });
    }

    // Configure validation rules
    const config = {
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedImageTypes: ['image/jpeg', 'image/png'],
        requiredFields: ['hotelName', 'hotelLocation', 'hotelDescription']
    };

    // Initialize preview areas
    function setupImagePreviews() {
        // Preview sections in the form
        const uploadPreviews = ['preview1', 'preview2', 'preview3'];
        uploadPreviews.forEach(previewId => {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.style.display = 'none';
            }
        });

        // Live preview area
        const previewImages = ['previewMainImage', 'previewImage2', 'previewImage3'];
        previewImages.forEach(previewId => {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.style.display = 'none';
                const iconContainer = document.createElement('div');
                iconContainer.className = 'w-full h-full flex items-center justify-center bg-gray-100 rounded-lg';
                iconContainer.innerHTML = `
                    <svg class="w-12 h-24 text-gray-400"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                `;
                preview.parentNode.insertBefore(iconContainer, preview.nextSibling);
            }
        });
    }

    setupImagePreviews();

    // Handle image upload and preview
    function setupImageInput(inputId, formPreviewId, livePreviewId) {
        const input = document.getElementById(inputId);
        const formPreview = document.getElementById(formPreviewId);
        const livePreview = document.getElementById(livePreviewId);
        const wrapper = input.closest('.image-input-wrapper');
        const placeholder = wrapper.querySelector('.placeholder-text');
        const iconContainer = livePreview.nextElementSibling;

        wrapper.addEventListener('click', () => input.click());

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
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
                    // Update form preview
                    formPreview.src = e.target.result;
                    formPreview.style.display = 'block';
                    placeholder.style.display = 'none';

                    // Update live preview
                    livePreview.src = e.target.result;
                    livePreview.style.display = 'block';
                    iconContainer.style.display = 'none';
                };
                reader.readAsDataURL(file);
                hideError('imageError');
            }
        });

        // Drag and drop handling
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
            const file = e.dataTransfer.files[0];
            if (file) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    }

    // Set up image inputs
    setupImageInput('hotelImage1', 'preview1', 'previewMainImage');
    setupImageInput('hotelImage2', 'preview2', 'previewImage2');
    setupImageInput('hotelImage3', 'preview3', 'previewImage3');

    // Handle live preview updates
    function setupLivePreview() {
        const elements = {
            name: { input: 'hotelName', preview: 'previewName' },
            location: { input: 'hotelLocation', preview: 'previewLocation' },
            description: { input: 'hotelDescription', preview: 'previewDescription' }
        };

        Object.entries(elements).forEach(([key, { input, preview }]) => {
            const inputElement = document.getElementById(input);
            const previewElement = document.getElementById(preview);

            inputElement.addEventListener('input', () => {
                previewElement.textContent = inputElement.value || `Enter ${key}...`;
                hideError(`${input}Error`);
            });
        });
    }

    setupLivePreview();

    // Handle amenities preview
    const amenitiesContainer = document.getElementById('previewAmenities');
    const amenityIcons = {
        wifi: '📶',
        pool: '🏊',
        spa: '💆',
        restaurant: '🍽️',
        valet: '🚗',
        concierge: '👨‍💼'
    };

    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateAmenitiesPreview);
    });

    function updateAmenitiesPreview() {
        amenitiesContainer.innerHTML = '';
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
            const badge = document.createElement('span');
            badge.className = 'inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm mr-2 mb-2';
            badge.innerHTML = `${amenityIcons[checkbox.id] || ''} ${checkbox.nextElementSibling.textContent.trim()}`;
            amenitiesContainer.appendChild(badge);
        });
    }

    // Form submission handler
    // Update the form submission handler in your JavaScript
    // Form submission handler
    hotelForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearErrors();

        if (!validateForm()) return;

        submitButton.disabled = true;
        loadingSpinner.style.display = 'inline-block';

        try {
            const formData = new FormData();

            // Add text fields
            formData.append('hotelName', document.getElementById('hotelName').value);
            formData.append('hotelLocation', document.getElementById('hotelLocation').value);
            formData.append('hotelDescription', document.getElementById('hotelDescription').value);

            // Add images
            const imageInputs = ['hotelImage1', 'hotelImage2', 'hotelImage3'];
            imageInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input.files[0]) {
                    formData.append(inputId, input.files[0]);
                }
            });

            // Add amenities
            const amenities = ['wifi', 'pool', 'spa', 'restaurant', 'valet', 'concierge'];
            amenities.forEach(amenity => {
                const checkbox = document.getElementById(amenity);
                if (checkbox.checked) {
                    formData.append(amenity, '1');
                }
            });

            // Log formData contents
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            const response = await fetch('../../actions/create_hotel.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                showNotification('Hotel created successfully!', 'success');
                setTimeout(() => window.location.href = data.redirect, 1500);
            } else {
                const errorMessage = Array.isArray(data.errors)
                    ? data.errors.join('\n')
                    : 'Failed to create hotel';
                throw new Error(errorMessage);
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(
                error.message || 'An unexpected error occurred while creating the hotel',
                'error'
            );
        } finally {
            submitButton.disabled = false;
            loadingSpinner.style.display = 'none';
        }
    });


    // Helper functions
    function validateForm() {
        let isValid = true;

        config.requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                showError(`${field}Error`, `${field.replace('hotel', '').replace(/([A-Z])/g, ' $1').trim()} is required`);
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

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 px-6 py-3 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
        notification.textContent = message;
        console.log('Error message:', message);
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 10000);
    }
});