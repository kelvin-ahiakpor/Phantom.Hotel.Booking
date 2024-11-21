document.addEventListener("DOMContentLoaded", function () {
  const bookingsContainer = document.getElementById("bookings-container");

  // Fetch bookings dynamically from the server
  fetch("../../actions/getBookings.php")
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

  // Helper function to create a booking card
  function createBookingCard(booking) {
    const checkInDate = moment(booking.check_in_date).format("MMM D, YYYY");
    const checkOutDate = moment(booking.check_out_date).format("MMM D, YYYY");
    const nights = moment(booking.check_out_date).diff(
      moment(booking.check_in_date),
      "days"
    );

    const card = document.createElement("div");
    card.className =
      "bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:shadow-2xl";
    card.innerHTML = `
      <div class="flex flex-col lg:flex-row">
        <div class="lg:w-1/3 relative">
          <img src="../../assets/images/placeholder.jpg" alt="${booking.room_type}" class="h-48 lg:h-full w-full object-cover">
          <div class="absolute top-4 left-4">
            <span class="px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
              Confirmed
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
              <p class="mt-1 font-medium text-gray-900">${checkInDate}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Check-out</p>
              <p class="mt-1 font-medium text-gray-900">${checkOutDate}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Duration</p>
              <p class="mt-1 font-medium text-gray-900">${nights} Nights</p>
            </div>
          </div>
          <div class="mt-6 flex justify-end space-x-4">
            <button class="border border-black px-4 py-2 text-sm font-medium text-black hover:bg-black hover:text-white transition duration-150">
              Modify
            </button>
            <button class="px-4 py-2 bg-black text-white hover:bg-zinc-600 transition duration-150">
              Cancel
            </button>
          </div>
        </div>
      </div>
    `;
    return card;
  }

  // Helper function to display bookings
  function displayBookings(bookings) {
    if (bookings.length === 0) {
      bookingsContainer.innerHTML = `<p class="text-gray-600">No bookings found.</p>`;
      return;
    }

    bookings.forEach((booking) => {
      bookingsContainer.appendChild(createBookingCard(booking));
    });
  }

  // Logout functionality
  document.getElementById("signOutBtn").addEventListener("click", function () {
    window.location.href = "../../actions/logout.php";
  });
});
