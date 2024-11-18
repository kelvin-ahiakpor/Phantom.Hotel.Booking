// Sample booking data (in a real application, this would come from an API)
const bookings = [
    {
      id: "BOK001",
      roomType: "Deluxe Ocean Suite",
      checkIn: "2024-11-15",
      checkOut: "2024-11-20",
      guests: 2,
      price: 1500,
      status: "confirmed",
      amenities: ["Ocean View", "King Bed", "Private Balcony"],
      image: "../../assets/images/linus-mimietz-p3UWyaujtQo-unsplash.jpg",
      width: 400,
      height: 300
    },
    {
      id: "BOK002",
      roomType: "Presidential Suite",
      checkIn: "2024-12-24",
      checkOut: "2024-12-31",
      guests: 4,
      price: 3000,
      status: "pending",
      amenities: ["City View", "2 King Beds", "Private Pool"],
      image: "../../assets/images/linus-mimietz-p3UWyaujtQo-unsplash.jpg",
      width: 400,
      height: 300
    },
  ];

  function createBookingCard(booking) {
    const checkInDate = moment(booking.checkIn).format("MMM D, YYYY");
    const checkOutDate = moment(booking.checkOut).format("MMM D, YYYY");
    const nights = moment(booking.checkOut).diff(
      moment(booking.checkIn),
      "days"
    );

    const card = document.createElement("div");
    card.className =
      "bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:shadow-2xl";
    card.innerHTML = `
      <div class="flex flex-col lg:flex-row">
        <div class="lg:w-1/3 relative">
          <img src="${booking.image}" alt="${
      booking.roomType
    }" class="h-${booking.height} lg:h-full w-${booking.width} object-cover">
          <div class="absolute top-4 left-4">
            <span class="px-4 py-2 rounded-full text-sm font-medium ${
              booking.status === "confirmed"
                ? "bg-green-100 text-green-800"
                : "bg-yellow-100 text-yellow-800"
            }">${
      booking.status.charAt(0).toUpperCase() + booking.status.slice(1)
    }</span>
          </div>
        </div>
        <div class="p-6 lg:p-8 lg:w-2/3">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-xl font-bold text-gray-900">${
                booking.roomType
              }</h3>
              <p class="text-sm text-gray-600 mt-1">Booking ID: ${
                booking.id
              }</p>
            </div>
            <p class="text-2xl font-bold text-gray-900">$${booking.price.toLocaleString()}</p>
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
              <p class="text-sm font-medium text-gray-500">Guests</p>
              <p class="mt-1 font-medium text-gray-900">${
                booking.guests
              } Adults</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Duration</p>
              <p class="mt-1 font-medium text-gray-900">${nights} Nights</p>
            </div>
          </div>
          <div class="mt-4">
            <p class="text-sm font-medium text-gray-500">Amenities</p>
            <div class="mt-2 flex flex-wrap gap-2">
              ${booking.amenities
                .map(
                  (amenity) => `
                <span class="px-3 py-1 bg-gray-200 rounded-full text-sm text-gray-700 font-medium">${amenity}</span>
              `
                )
                .join("")}
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

  function displayBookings() {
    const container = document.getElementById("bookings-container");
    bookings.forEach((booking) => {
      container.appendChild(createBookingCard(booking));
    });
  }
  displayBookings();