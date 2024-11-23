document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements - Modals
    const roomModal = document.getElementById('roomModal');
    const deleteModal = document.getElementById('deleteModal');
    const profileModal = document.getElementById('profileModal');

    // DOM Elements - Buttons
    const addRoomBtn = document.getElementById('addRoomBtn');
    const closeRoomModalBtn = document.getElementById('closeRoomModal'); // Renamed
    const cancelRoomBtn = document.getElementById('cancelRoomBtn');
    const profileBtn = document.getElementById('profileBtn');
    const closeProfileModal = document.getElementById('closeProfileModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

    // DOM Elements - Forms
    const roomForm = document.getElementById('roomForm');
    const deleteRoomForm = document.getElementById('deleteRoomForm');

    // DOM Elements - Form Fields
    const roomIdInput = document.getElementById('roomId');
    const roomTypeInput = document.getElementById('roomType');
    const capacityInput = document.getElementById('capacity');
    const priceInput = document.getElementById('pricePerNight');
    const availabilityInput = document.getElementById('availability');
    const deleteRoomIdInput = document.getElementById('deleteRoomId');
    const modalTitle = document.getElementById('modalTitle');
    const submitBtnText = document.getElementById('submitBtnText');

    // Constants
    const MODAL_MODES = {
        ADD: 'add',
        EDIT: 'edit'
    };

    let currentMode = MODAL_MODES.ADD;

    // Profile Modal Handlers
    function initializeProfileModal() {
        profileBtn.addEventListener('click', () => toggleModal(profileModal, true));
        closeProfileModal.addEventListener('click', () => toggleModal(profileModal, false));

        document.addEventListener('click', (e) => {
            if (!profileModal.contains(e.target) && !profileBtn.contains(e.target)) {
                toggleModal(profileModal, false);
            }
        });
    }

    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    }

    function clearErrors() {
        document.querySelectorAll('.error-text').forEach(element => {
            element.style.display = 'none';
            element.textContent = '';
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

    // Room Modal Handlers
    function initializeRoomModal() {
        // Fixed this line - now calls openRoomModal with default mode
        addRoomBtn.addEventListener('click', () => openRoomModal(MODAL_MODES.ADD));

        // Fixed these lines - now using proper function references
        closeRoomModalBtn.addEventListener('click', () => handleCloseRoomModal());
        cancelRoomBtn.addEventListener('click', () => handleCloseRoomModal());

        // Handle edit button clicks
        document.querySelectorAll('.edit-room-btn').forEach(button => {
            button.addEventListener('click', () => {
                const roomData = {
                    roomId: button.dataset.roomId,
                    roomType: button.dataset.roomType,
                    capacity: button.dataset.capacity,
                    price: button.dataset.price,
                    availability: button.dataset.availability
                };
                openRoomModal(MODAL_MODES.EDIT, roomData);
            });
        });
    }

    // Delete Modal Handlers
    function initializeDeleteModal() {
        document.querySelectorAll('.delete-room-btn').forEach(button => {
            button.addEventListener('click', () => {
                deleteRoomIdInput.value = button.dataset.roomId;
                toggleModal(deleteModal, true);
            });
        });

        cancelDeleteBtn.addEventListener('click', () => toggleModal(deleteModal, false));
    }

    // Modal Functions
    function openRoomModal(mode = MODAL_MODES.ADD, roomData = null) {
        currentMode = mode;
        modalTitle.textContent = mode === MODAL_MODES.ADD ? 'Add New Room' : 'Edit Room';
        submitBtnText.textContent = mode === MODAL_MODES.ADD ? 'Add Room' : 'Save Changes';

        if (mode === MODAL_MODES.ADD) {
            roomForm.reset();
            roomIdInput.value = '';
            availabilityInput.checked = true;
        } else if (roomData) {
            roomIdInput.value = roomData.roomId;
            roomTypeInput.value = roomData.roomType;
            capacityInput.value = roomData.capacity;
            priceInput.value = roomData.price;
            availabilityInput.checked = roomData.availability === "1";
        }

        toggleModal(roomModal, true);
        roomTypeInput.focus();
    }

    // Renamed this function to avoid conflicts
    function handleCloseRoomModal() {
        toggleModal(roomModal, false);
        clearErrors();
        roomForm.reset();
    }

    function toggleModal(modal, show) {
        if (show) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    function handleRoomSubmit(e) {
        e.preventDefault();
        clearErrors();

        if (!validateRoomForm()) {
            return;
        }

        const submitButton = roomForm.querySelector('button[type="submit"]');
        const loadingSpinner = submitButton.querySelector('.loading-spinner');

        submitButton.disabled = true;
        loadingSpinner.classList.remove('hidden');

        const formData = new FormData(roomForm);
        formData.append('availability', availabilityInput.checked ? '1' : '0');

        const url = currentMode === MODAL_MODES.ADD
            ? '../../actions/createRoom.php'
            : '../../actions/updateRoom.php';

        fetch(url, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(
                        `Room ${currentMode === MODAL_MODES.ADD ? 'created' : 'updated'} successfully`,
                        'success'
                    );
                    location.reload();
                } else {
                    throw new Error(data.errors?.join('\n') || 'Failed to save room');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message, 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                loadingSpinner.classList.add('hidden');
                handleCloseRoomModal();
            });
    }

    function handleDeleteSubmit(e) {
        e.preventDefault();

        const submitButton = deleteRoomForm.querySelector('button[type="submit"]');
        const loadingSpinner = submitButton.querySelector('.loading-spinner');

        submitButton.disabled = true;
        loadingSpinner.classList.remove('hidden');

        fetch('../../actions/deleteRoom.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `roomId=${deleteRoomIdInput.value}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Room deleted successfully', 'success');
                    location.reload();
                } else {
                    throw new Error(data.error || 'Failed to delete room');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message, 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                loadingSpinner.classList.add('hidden');
                toggleModal(deleteModal, false);
            });
    }

    // Also add these validation functions
    function validateRoomForm() {
        let isValid = true;

        // Room Type
        if (!roomTypeInput.value.trim()) {
            showError('roomTypeError', 'Room type is required');
            isValid = false;
        }

        // Capacity
        const capacity = parseInt(capacityInput.value);
        if (isNaN(capacity) || capacity < 1) {
            showError('capacityError', 'Capacity must be at least 1');
            isValid = false;
        }

        // Price
        const price = parseFloat(priceInput.value);
        if (isNaN(price) || price <= 0) {
            showError('priceError', 'Please enter a valid price');
            isValid = false;
        }

        return isValid;
    }

    // The rest of your code remains the same...
    // (Form handling, validation, notifications, etc.)

    // Initialize Event Listeners
    function initialize() {
        initializeProfileModal();
        initializeRoomModal();
        initializeDeleteModal();

        // Form submissions
        roomForm.addEventListener('submit', handleRoomSubmit);
        deleteRoomForm.addEventListener('submit', handleDeleteSubmit);

        // Input event listeners to clear errors
        roomTypeInput.addEventListener('input', () => hideError('roomTypeError'));
        capacityInput.addEventListener('input', () => hideError('capacityError'));
        priceInput.addEventListener('input', () => hideError('priceError'));

        // Modal backdrop clicks
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) {
                    const modal = e.target.closest('.modal');
                    toggleModal(modal, false);
                }
            });
        });
    }

    function hideError(elementId) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    // Start the application
    initialize();
});