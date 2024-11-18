<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Phantom Booking</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/css/styles-hotel_feed.css">
  </head>
  <body class="bg-gray-50">
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div
        class="container mx-auto px-4 py-3 flex items-center justify-between"
      >
        <div class="text-2xl font-serif text-gray-800">Phantom</div>
        <div class="flex items-center space-x-4">
          <button class="text-gray-600 hover:text-gray-800" id="profile-btn">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="25"
              height="25"
              fill="currentColor"
              class="bi bi-person"
              viewBox="0 0 16 16"
            >
              <path
                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"
              />
            </svg>
          </button>
        </div>
      </div>
    </header>
    <div
      class="hidden fixed top-14 right-5 w-auto bg-white p-6 rounded-lg shadow-lg"
      id="profile-modal"
    >
      <div class="flex items-center space-x-4">
        <img
          src="https://via.placeholder.com/50"
          alt="Profile"
          class="rounded-full"
        />
        <div>
          <h2 class="text-lg font-medium">Bryan</h2>
          <p class="text-gray-500">bryanhans19@outlook.com</p>
          <a href="#" class="text-blue-500 hover:text-blue-600"
            >Manage account</a
          >
        </div>
      </div>
      <button class="text-gray-500 hover:text-gray-600 mt-4" id="close-modal">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          fill="currentColor"
          class="bi bi-x-lg"
          viewBox="0 0 16 16"
        >
          <path
            d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"
          />
        </svg>
      </button>
    </div>

    <main class="min-h-screen">
      <!-- Search Section -->
      <section class="container mx-auto px-4 py-8">
        <div class="bg-white w-full shadow-lg p-6 mb-8">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Destination</label
              >
              <input
                type="text"
                placeholder="Where are you going?"
                class="w-full p-2 border rounded-lg"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Check-in</label
              >
              <input type="date" class="w-full p-2 border rounded-lg" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Check-out</label
              >
              <input type="date" class="w-full p-2 border rounded-lg" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Guests</label
              >
              <select class="w-full p-2 border rounded-lg">
                <option>1 Guest</option>
                <option>2 Guests</option>
                <option>3 Guests</option>
                <option>4+ Guests</option>
              </select>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap gap-4 mb-8">
          <button
            class="border border-black px-4 py-2 text-xl text-black hover:bg-black hover:text-white transition duration-300"
          >
            Price Range
          </button>
          <button
            class="border border-black px-4 py-2 text-xl text-black hover:bg-black hover:text-white transition duration-300"
          >
            Property Type
          </button>
          <button
            class="border border-black px-4 py-2 text-xl text-black hover:bg-black hover:text-white transition duration-300"
          >
            Amenities
          </button>
          <button
            class="border border-black px-4 py-2 text-xl text-black hover:bg-black hover:text-white transition duration-300"
          >
            Rating
          </button>
        </div>
      </section>

      <!-- Carousel Section -->
      <section class="relative w-full bg-gray-900 py-12 md:py-16">
        <div class="container mx-auto px-4">
          <div class="card-container relative flex items-center justify-center">
            <!-- Navigation -->
            <button
              id="prevBtn"
              class="absolute left-4 z-50 text-white hover:scale-110 transition bg-black/50 p-2 rounded-full"
            >
              ←
            </button>
            <button
              id="nextBtn"
              class="absolute right-4 z-50 text-white hover:scale-110 transition bg-black/50 p-2 rounded-full"
            >
              →
            </button>

            <!-- Cards Container -->
            <div
              id="carousel"
              class="relative w-full flex items-center justify-center"
            ></div>

            <!-- Pagination -->
            <div
              class="absolute bg-white rounded-lg shadow-xl ${position} w-[300px] md:w-[400px] flex items-center justify-center"
            >
              <span id="current-slide" class="text-black text-lg">1</span>
              <span class="text-black text-lg">/</span>
              <span id="total-slides" class="text-black text-lg">5</span>
            </div>
          </div>
        </div>
      </section>

      <section class="container mx-auto px-4 py-8 text-center">
        <button
          class="border-2 border-black px-8 py-4 text-xl text-black uppercase tracking-widest hover:bg-black hover:text-white transition duration-300"
        >
          Load More Hotels
        </button>
      </section>

      <section class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-4">Hotel Openings</h2>
        <div
          class="grid grid-cols-1 md:grid-cols-3 gap-4"
          id="article-grid"
        ></div>
      </section>

      <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 flex justify-between items-center">
          <div class="text-sm">
            © 2024 Phantom Booking, L.L.C. All rights reserved.
          </div>
          <div class="space-x-4">
            <a href="#" class="hover:text-gray-400">Terms of Use</a>
            <a href="#" class="hover:text-gray-400">BEIAN CN Site</a>
            <a href="#" class="hover:text-gray-400">Do Not Sell Information</a>
          </div>
        </div>
      </footer>
    </main>
    <script src="../assets/js/hotel_feed.js"></script> 
  </body>
</html>
