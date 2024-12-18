import checkInternetConnection from '../../utils/checkInternetConnection.js';

// Service Worker Registration
if ('serviceWorker' in navigator) {
    navigator.serviceWorker
        .register('/sw.js') // Ensure sw.js is in the root directory
        .then(registration => {
            console.log('Service Worker registered with scope:', registration.scope);
        })
        .catch(error => {
            console.error('Service Worker registration failed:', error);
        });
}

function handleOffline() {
    alert("No internet connection. Redirecting to offline page...");
    window.location.href = "../no_internet.html"; 
}

function handleOnline() {
    console.log("Back online!");
}

checkInternetConnection(handleOffline, handleOnline);

document.addEventListener("DOMContentLoaded", function () {
    const submitBookingBtn = document.getElementById("submitBooking");
    const formFeedback = document.getElementById("formFeedback");
    const hotelId = document.querySelector('input[name="hotel_id"]').value;
    const roomSelect = document.getElementById("room_id");
    const today = new Date().toISOString().split("T")[0];
    const checkInInput = document.getElementById("check_in_date")
    const checkOutInput = document.getElementById("check_out_date")

    // Set the min attribute for both check-in and check-out dates
    checkInInput.setAttribute("min", today);
    checkOutInput.setAttribute("min", today);

    // Update check-out min date when check-in changes
    checkInInput.addEventListener("change", function () {
        checkOutInput.setAttribute("min", this.value);
    });

    // Track the selected room
    let selectedRoom = null;

    // Fetch available rooms on page load
    fetch(`../../actions/getAvailableRooms.php?hotel_id=${hotelId}`)
        .then((response) => {
            console.log("Response status:", response.status);
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                // Populate the dropdown
                roomSelect.innerHTML = data.rooms
                    .map(
                        (room) =>
                            `<option value="${room.room_id}">
                                ${room.room_type} - $${parseFloat(room.price_per_night).toFixed(2)}/night (Capacity: ${room.capacity})
                            </option>`
                    )
                    .join("");

                // Set default selected room
                const defaultRoomId = roomSelect.value; // First option value
                selectedRoom = data.rooms.find(
                    (room) => room.room_id === parseInt(defaultRoomId)
                );

                console.log("Default selected room:", selectedRoom);
            } else {
                // Handle no rooms available
                roomSelect.innerHTML = `<option value="">${data.message}</option>`;
            }
        })
        .catch((error) => {
            console.error("Error fetching rooms:", error);
            roomSelect.innerHTML = `<option value="">Failed to load rooms. Error: ${error.message}</option>`;
        });

    // Update selectedRoom when the user changes the dropdown
    roomSelect.addEventListener("change", function () {
        const selectedRoomId = parseInt(roomSelect.value);
        fetch(`../../actions/getAvailableRooms.php?hotel_id=${hotelId}`)
            .then((response) => response.json())
            .then((data) => {
                selectedRoom = data.rooms.find(
                    (room) => room.room_id === selectedRoomId
                );
                console.log("Selected room updated:", selectedRoom);
            })
            .catch((error) => console.error("Error updating selected room:", error));
    });

    // Handle form submission
    submitBookingBtn.addEventListener("click", function (e) {
        e.preventDefault();

        // Form field values
        const today = new Date().toISOString().split("T")[0];
        const roomId = roomSelect.value;
        const checkIn = document.getElementById("check_in_date").value;
        const checkOut = document.getElementById("check_out_date").value;
        const guests = document.getElementById("guests").value;

        // Clear previous feedback
        formFeedback.textContent = "";
        formFeedback.className = "";

        // Validation
        let isValid = true;
        const errors = [];

        if (!checkIn) {
            isValid = false;
            errors.push("Check-in date is required.");
        }

        if (!checkOut) {
            isValid = false;
            errors.push("Check-out date is required.");
        }

        if (checkIn && checkOut && new Date(checkOut) <= new Date(checkIn)) {
            isValid = false;
            errors.push("Check-out date must be after the check-in date.");
        }

        if (!guests || isNaN(guests) || guests <= 0) {
            isValid = false;
            errors.push("Please enter a valid number of guests.");
        }

        if (selectedRoom && guests > selectedRoom.capacity) {
            isValid = false;
            errors.push(`Number of guests exceeds room capacity (Max: ${selectedRoom.capacity}).`);
        }

        if (!roomId) {
            isValid = false;
            errors.push("Please select a room.");
        }

        if (checkIn < today) {
            isValid = false;
            errors.push("Check-in date cannot be in the past.");
        }
    
        if (checkOut <= checkIn) {
            isValid = false;
            errors.push("Check-out date must be after the check-in date.");
        }

        if (!isValid) {
            formFeedback.textContent = errors.join(" ");
            formFeedback.className = "text-red-500";
            return;
        }

        // Prepare form data for submission
        const formData = {
            hotel_id: hotelId,
            room_id: roomId,
            check_in: checkIn,
            check_out: checkOut,
            guests: guests,
        };

        // Send AJAX request to submit booking
        fetch("../../actions/submitBooking.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    formFeedback.textContent = "Booking confirmed! Redirecting...";
                    formFeedback.className = "text-green-500";
                    setTimeout(() => {
                        window.location.href = "bookings.php";
                    }, 2000);
                } else {
                    formFeedback.textContent = data.message || "Failed to process booking.";
                    formFeedback.className = "text-red-500";
                }
            })
            .catch((error) => {
                formFeedback.textContent = "An error occurred. Please try again.";
                formFeedback.className = "text-red-500";
                console.error("Error:", error);
            });
    });
});