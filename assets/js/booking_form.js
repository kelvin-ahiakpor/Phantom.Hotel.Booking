document.addEventListener("DOMContentLoaded", function () {
    const submitBookingBtn = document.getElementById("submitBooking");
    const formFeedback = document.getElementById("formFeedback");

    submitBookingBtn.addEventListener("click", function (e) {
        e.preventDefault();

        const formData = {
            hotel_id: document.querySelector('input[name="hotel_id"]').value,
            check_in: document.getElementById("check_in_date").value,
            check_out: document.getElementById("check_out_date").value,
            guests: document.getElementById("guests").value
        };

        fetch("../../actions/submitBooking.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    formFeedback.textContent = "Booking confirmed! Redirecting...";
                    formFeedback.className = "text-green-500";
                    setTimeout(() => {
                        window.location.href = "bookings.php";
                    }, 2000);
                } else {
                    formFeedback.textContent = data.message;
                    formFeedback.className = "text-red-500";
                }
            })
            .catch(error => {
                formFeedback.textContent = "An error occurred. Please try again.";
                formFeedback.className = "text-red-500";
                console.error("Error:", error);
            });
    });
});