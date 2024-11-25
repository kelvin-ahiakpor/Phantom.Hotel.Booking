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
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
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

  // Improved Booking card with status and action buttons
  function createBookingCard(booking) {
    const statusClass = {
      confirmed: "bg-green-100 text-green-800",
      pending: "bg-yellow-100 text-yellow-800",
      cancelled: "bg-red-100 text-red-800"
    }[booking.status] || "";

    const card = document.createElement("div");
    card.className = "bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:shadow-2xl";

    card.innerHTML = `
        <div class="relative">
            <!-- Image Section -->
            <div class="h-56 relative">
                <img src="${booking.image_url}" alt="${booking.room_type}" 
                     class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <div class="absolute top-4 left-4">
                    <span class="px-4 py-2 rounded-full text-sm font-medium ${statusClass} shadow-lg">
                        ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                    </span>
                </div>
                <div class="absolute bottom-4 left-4 right-4">
                    <h3 class="text-xl font-bold text-white mb-1">
                        ${booking.hotel_name}
                    </h3>
                    <p class="text-white/90 text-sm">
                        ${booking.room_type}
                    </p>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6 space-y-6">
                <!-- Booking Details -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2 text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Booking ID: ${booking.booking_id}</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <span class="text-2xl font-bold text-gray-900">
                            $${parseFloat(booking.total_price).toFixed(2)}
                        </span>
                        <span class="text-gray-500">
                            ${moment(booking.check_out_date).diff(moment(booking.check_in_date), "days")} Nights
                        </span>
                    </div>
                </div>

                <!-- Dates and Guests Grid -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-500">Check-in</p>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="font-medium text-gray-900">
                                ${moment(booking.check_in_date).format("MMM D, YYYY")}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-500">Check-out</p>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="font-medium text-gray-900">
                                ${moment(booking.check_out_date).format("MMM D, YYYY")}
                            </p>
                        </div>
                    </div>

                    <div class="col-span-2 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="font-medium text-gray-900">${booking.guests} Guests</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                ${booking.status === "confirmed" || booking.status === "pending" ? `
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-100">
                        <button class="modify-btn px-6 py-2.5 border border-black text-sm font-medium text-black hover:bg-black hover:text-white transition duration-150" 
                            data-booking-id="${booking.booking_id}"
                            data-check-in="${booking.check_in_date}"
                            data-check-out="${booking.check_out_date}"
                            data-guests="${booking.guests}">
                            Modify
                        </button>
                        <button class="cancel-btn px-6 py-2.5 bg-black text-white text-sm font-medium hover:bg-zinc-600 transition duration-150" 
                            data-booking-id="${booking.booking_id}">
                            Cancel
                        </button>
                    </div>
                ` : ''}
            </div>
        </div>
    `;

    // Add event listeners for buttons
    if (booking.status === "confirmed" || booking.status === "pending") {
      const modifyBtn = card.querySelector(".modify-btn");
      const cancelBtn = card.querySelector(".cancel-btn");

      modifyBtn.addEventListener("click", (e) => {
        openModifyModal(
          e.target.dataset.bookingId,
          e.target.dataset.checkIn,
          e.target.dataset.checkOut,
          e.target.dataset.guests
        );
      });

      cancelBtn.addEventListener("click", () => {
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

  function openCancelModal(bookingId) {
    const modalHtml = `
      <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-6 w-1/3">
          <h2 class="text-xl font-bold mb-4">Cancel Booking</h2>
          <p>Are you sure you want to cancel this booking?</p>
          <div class="flex justify-end space-x-4 mt-4">
            <button id="closeCancelModal" class="px-4 py-2 border border-gray-400 rounded">No</button>
            <button id="confirmCancel" class="px-4 py-2 bg-red-500 text-white rounded">Yes</button>
          </div>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML("beforeend", modalHtml);

    document.getElementById("closeCancelModal").addEventListener("click", () => {
      document.getElementById("cancelModal").remove();
    });

    document.getElementById("confirmCancel").addEventListener("click", () => {
      fetch("../../actions/cancelBooking.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ booking_id: bookingId }),
      })
        .then((response) => response.json())
        .then((data) => {
          document.getElementById("cancelModal").remove();
          showSuccessModal(data.message); // Show success modal
          if (data.success) {
            setTimeout(() => location.reload(), 2000); // Refresh bookings after success
          }
        })
        .catch((error) => {
          console.error("Error cancelling booking:", error);
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