document.addEventListener("DOMContentLoaded", function () {
    const submitBookingBtn = document.getElementById("submitBooking");
    const formFeedback = document.getElementById("formFeedback");

    submitBookingBtn.addEventListener("click", function (e) {
        e.preventDefault();

        // Form field values
        const hotelId = document.querySelector('input[name="hotel_id"]').value;
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

        if (!isValid) {
            formFeedback.textContent = errors.join(" ");
            formFeedback.className = "text-red-500";
            return;
        }

        // Prepare form data for submission
        const formData = {
            hotel_id: hotelId,
            check_in: checkIn,
            check_out: checkOut,
            guests: guests,
        };

        // Send AJAX request to the server
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
