document.addEventListener("DOMContentLoaded", function () {
    function debounce(func, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    const hotelSearch = document.getElementById("hotelSearch");
    const dropdownResults = document.getElementById("dropdownResults");
    const hotelGrid = document.getElementById("hotelGrid");

    const fetchHotels = (searchTerm = "") => {
        fetch(`../../actions/searchHotels.php?search=${encodeURIComponent(searchTerm)}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    updateHotelGrid(data.hotels);
                    updateDropdown(data.hotels, searchTerm);
                } else {
                    hotelGrid.innerHTML = "<p class='text-gray-500'>No hotels found.</p>";
                    updateDropdown([], searchTerm);
                }
            })
            .catch((error) => {
                console.error("Error fetching hotels:", error);
            });
    };

    const updateHotelGrid = (hotels) => {
        hotelGrid.innerHTML = hotels
            .map(
                (hotel) => `
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="${hotel.image || '../../assets/images/placeholder.jpg'}" alt="${hotel.hotel_name}" class="h-48 w-full object-cover">
                    <div class="p-6">
                        <h3 class="text-lg font-bold">${hotel.hotel_name}</h3>
                        <p class="text-sm text-gray-600">${hotel.location}</p>
                        <p class="text-sm text-gray-800 mt-2">${hotel.description}</p>
                        <p class="text-xl font-bold mt-4">
                            $${parseFloat(hotel.min_price).toFixed(2)} - $${parseFloat(hotel.max_price).toFixed(2)}/night
                        </p>
                        <button
                            class="mt-4 px-4 py-2 bg-black text-white hover:bg-zinc-600 transition duration-150"
                            onclick="window.location.href='booking_form.php?hotel_id=${hotel.hotel_id}';"
                        >
                            Book Now
                        </button>
                    </div>
                </div>
            `
            )
            .join("");
    };

    const updateDropdown = (hotels, searchTerm) => {
        if (searchTerm.trim() === "") {
            dropdownResults.classList.add("hidden");
            return;
        }

        if (hotels.length === 0) {
            dropdownResults.innerHTML = `<div class="p-2 text-gray-500">No results found</div>`;
            dropdownResults.classList.remove("hidden");
            return;
        }

        dropdownResults.innerHTML = hotels
            .map(
                (hotel) => `
                <div class="p-2 hover:bg-gray-100 cursor-pointer" data-hotel-id="${hotel.hotel_id}">
                    ${hotel.hotel_name.trim()} - ${hotel.location.trim()}
                </div>
            `
            )
            .join("");

        dropdownResults.classList.remove("hidden");

        dropdownResults.querySelectorAll("[data-hotel-id]").forEach((item) => {
            item.addEventListener("click", () => {
                const hotelName = item.textContent.trim();
                hotelSearch.value = hotelName; // Set the search value
                dropdownResults.classList.add("hidden");

                // Update grid without showing "No results"
                const selectedHotel = hotels.find(
                    (hotel) => `${hotel.hotel_name} - ${hotel.location}`.trim() === hotelName
                );
                if (selectedHotel) {
                    updateHotelGrid([selectedHotel]);
                }
            });
        });
    };

    // Handle outside click to reset search
    document.addEventListener("click", (event) => {
        if (!hotelSearch.contains(event.target) && !dropdownResults.contains(event.target)) {
            dropdownResults.classList.add("hidden");
            hotelSearch.value = ""; // Clear the search bar
            fetchHotels(); // Reset to all hotels
        }
    });

    // Debounce input for smoother searching
    hotelSearch.addEventListener(
        "input",
        debounce(() => {
            const searchTerm = hotelSearch.value.trim();
            if (searchTerm) {
                fetchHotels(searchTerm);
            } else {
                fetchHotels(); // Fetch all hotels if search is cleared
            }
        }, 300)
    );

    // Initial fetch to display all the hotels
    fetchHotels();
});