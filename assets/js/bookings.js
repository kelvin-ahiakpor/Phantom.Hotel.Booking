document.addEventListener("DOMContentLoaded", function () {
  const bookingsContainer = document.getElementById("bookings-container");
  const filterDropdown = document.querySelector("select");
  const today = new Date().toISOString().split("T")[0];
  const checkInInput = document.getElementById("check_in_date");
  const checkOutInput = document.getElementById("check_out_date");

  // Load bookings on page load
  fetchBookings("all");
  
  // Fetch bookings when filter changes
  filterDropdown.addEventListener("change", function () {
    const selectedStatus = this.value;
    fetchBookings(selectedStatus);
  });

  // Fetch bookings based on status
  function fetchBookings(status) {
    bookingsContainer.innerHTML = `<p class="text-gray-600">Loading...</p>`;
    const statusQuery = status !== "all" ? `?status=${status}` : "";
    fetch(`../../actions/getBookings.php${statusQuery}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          displayBookings(data.bookings);
        } else {
          bookingsContainer.innerHTML = `<p class="text-red-500">${data.message}</p>`;
        }
      })
      .catch((error) => {
        bookingsContainer.innerHTML = `<p class="text-red-500">Error loading bookings: ${error.message}</p>`;
      });
  }

  // Helper function to create a booking card
  function createBookingCard(booking) {
    const statusClass =
      booking.status === "confirmed"
        ? "bg-green-100 text-green-800"
        : booking.status === "pending"
        ? "bg-yellow-100 text-yellow-800"
        : booking.status === "cancelled"
        ? "bg-red-100 text-red-800"
        : "";
  
    const card = document.createElement("div");
    card.className =
      "bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:shadow-2xl";
  
    card.innerHTML = `
      <div class="flex flex-col lg:flex-row">
        <div class="lg:w-1/3 relative">
          <img src="../../assets/images/placeholder.jpg" alt="${booking.room_type}" class="h-48 lg:h-full w-full object-cover">
          <div class="absolute top-4 left-4">
            <span class="px-4 py-2 rounded-full text-sm font-medium ${statusClass}">
              ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
            </span>
          </div>
        </div>
        <div class="p-6 lg:p-8 lg:w-2/3">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-xl font-bold text-gray-900">${booking.hotel_name} - ${booking.room_type}</h3>
              <p class="text-sm text-gray-600 mt-1">Booking ID: ${booking.booking_id}</p>
            </div>
            <p class="text-2xl font-bold text-gray-900">$${parseFloat(booking.total_price).toFixed(2)}</p>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm font-medium text-gray-500">Check-in</p>
              <p class="mt-1 font-medium text-gray-900">${moment(booking.check_in_date).format("MMM D, YYYY")}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Check-out</p>
              <p class="mt-1 font-medium text-gray-900">${moment(booking.check_out_date).format("MMM D, YYYY")}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Duration</p>
              <p class="mt-1 font-medium text-gray-900">${moment(booking.check_out_date).diff(moment(booking.check_in_date), "days")} Nights</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Guests</p>
              <p class="mt-1 font-medium text-gray-900">${booking.guests}</p>
            </div>
          </div>
          <div class="mt-6 flex justify-end space-x-4">
            ${
              booking.status === "confirmed" || booking.status === "pending"
                ? `
                <button class="modify-btn border border-black px-4 py-2 text-sm font-medium text-black hover:bg-black hover:text-white transition duration-150" 
                  data-booking-id="${booking.booking_id}"
                  data-check-in="${booking.check_in_date}"
                  data-check-out="${booking.check_out_date}"
                  data-guests="${booking.guests}">
                  Modify
                </button>
                <button class="cancel-btn px-4 py-2 bg-black text-white hover:bg-zinc-600 transition duration-150" data-booking-id="${booking.booking_id}">
                  Cancel
                </button>`
                : ""
            }
          </div>
        </div>
      </div>
    `;
  
    // Attach event listeners for Modify and Cancel buttons
    if (booking.status === "confirmed" || booking.status === "pending") {
      card.querySelector(".modify-btn").addEventListener("click", (e) => {
        const button = e.target;
        openModifyModal(
          button.dataset.bookingId,
          button.dataset.checkIn,
          button.dataset.checkOut,
          button.dataset.guests
        );
      });
      card.querySelector(".cancel-btn").addEventListener("click", () => {
        openCancelModal(booking.booking_id);
      });
    }
  
    return card;
  }
  
  
  // Helper function to display bookings
  function displayBookings(bookings) {
    bookingsContainer.innerHTML = ""; // Clear existing content
    if (bookings.length === 0) {
      bookingsContainer.innerHTML = `<p class="text-gray-600">No bookings found.</p>`;
      return;
    }

    bookings.forEach((booking) => {
      bookingsContainer.appendChild(createBookingCard(booking));
    });
  }

  // Open Modify Modal
  function openModifyModal(bookingId, checkIn, checkOut, guests) {
    const today = new Date().toISOString().split("T")[0];
    const modalHtml = `
        <div id="modifyModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white rounded-lg p-6 w-1/3">
                <h2 class="text-xl font-bold mb-4">Modify Booking</h2>
                <label class="block text-sm mb-2">New Check-in Date:</label>
                <input id="newCheckIn" type="date" class="w-full border p-2 rounded mb-4" value="${checkIn}" min="${today}" />
                <label class="block text-sm mb-2">New Check-out Date:</label>
                <input id="newCheckOut" type="date" class="w-full border p-2 rounded mb-4" value="${checkOut}" min="${today}" />
                <label class="block text-sm mb-2">Number of Guests:</label>
                <input id="newGuests" type="number" class="w-full border p-2 rounded mb-4" value="${guests}" />
                <div class="flex justify-end space-x-4">
                    <button id="closeModifyModal" class="px-4 py-2 border border-gray-400 rounded">Cancel</button>
                    <button id="confirmModify" class="px-4 py-2 bg-blue-500 text-white rounded">Confirm</button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalHtml);
  
    document.getElementById("closeModifyModal").addEventListener("click", () => {
      document.getElementById("modifyModal").remove();
    });
  
    document.getElementById("confirmModify").addEventListener("click", () => {
      const today = new Date().toISOString().split("T")[0];
      const newCheckIn = document.getElementById("newCheckIn").value;
      const newCheckOut = document.getElementById("newCheckOut").value;
      const newGuests = document.getElementById("newGuests").value;
      const errors = [];
      let isValid = true;
  
      if (!newCheckIn || !newCheckOut || !newGuests) {
        showErrorModal("All fields are required.");
        return;
      }

      if (newCheckIn < today) {
        isValid = false;
        errors.push("Check-in date cannot be in the past.");
      }

      if (newCheckOut <= newCheckIn) {
          isValid = false;
          errors.push("Check-out date must be after the check-in date.");
      }

      if (!isValid) {
          showErrorModal(errors.join(" "));
          return;
      }
  
      fetch("../../actions/modifyBooking.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          booking_id: bookingId,
          check_in_date: newCheckIn,
          check_out_date: newCheckOut,
          guests: newGuests,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            showSuccessModal("Booking modified successfully.");
            setTimeout(() => location.reload(), 1500);
          } else {
            showErrorModal(data.message);
          }
        })
        .finally(() => {
          document.getElementById("modifyModal").remove();
        });
    });
  }
  
  function showSuccessModal(message) {
    const modalHtml = `
      <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-6 w-1/3">
          <h2 class="text-xl font-bold mb-4">Success</h2>
          <p>${message}</p>
          <div class="flex justify-end mt-4">
            <button id="closeSuccessModal" class="px-4 py-2 bg-blue-500 text-white rounded">OK</button>
          </div>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalHtml);

    document.getElementById("closeSuccessModal").addEventListener("click", () => {
      document.getElementById("successModal").remove();
    });
  }

  function showErrorModal(message) {
    const modalHtml = `
      <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-6 w-1/3">
          <h2 class="text-xl font-bold mb-4">Error</h2>
          <p>${message}</p>
          <div class="flex justify-end mt-4">
            <button id="closeErrorModal" class="px-4 py-2 bg-red-500 text-white rounded">OK</button>
          </div>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalHtml);

    document.getElementById("closeErrorModal").addEventListener("click", () => {
      document.getElementById("errorModal").remove();
    });
  }

  // Logout functionality
  document.getElementById("signOutBtn").addEventListener("click", function () {
    window.location.href = "../../actions/logout.php";
  });
});