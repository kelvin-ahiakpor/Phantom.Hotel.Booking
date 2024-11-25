<?php
require "../../functions/session_check.php";
require '../../db/config.php';
require "../middleware/checkInternetConnection.php";
checkInternetConnection();

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Phantom Booking</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
          <div class="flex items-center">
            <h1 class="text-3xl font-serif font-bold text-gray-800">Phantom</h1>
          </div>
          <div class="flex items-center space-x-6">
            <button
              class="text-black hover:text-gray-800 flex items-center space-x-2"
            >
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                />
              </svg>
              <span>My Account</span>
            </button>
            <button id="signOutBtn"
              class="text-black hover:text-gray-800 flex items-center space-x-2"
            >
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
              </svg>
              <span>Sign Out</span>
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
      <div class="mb-8 flex justify-between items-center">
        <div>
          <h2 class="text-3xl font-serif font-bold text-gray-900">
            Your Current Bookings
          </h2>
          <p class="mt-2 text-gray-600">
            View and manage your upcoming stays with us
          </p>
        </div>
        <div class="flex space-x-4">
        <select
          class="px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
          <option value="all">All Bookings</option>
          <option value="confirmed">Confirmed</option>
          <option value="pending">Pending</option>
          <option value="cancelled">Cancelled</option>
        </select>

          <button
          class="px-4 py-2 bg-black text-white hover:bg-zinc-600 transition duration-150"
          onclick="window.location.href='new_booking.php';"
        >
          New Booking
        </button>
        </div>
      </div>

      <div
        id="bookings-container"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
      ></div>
    </main>
    <script src="../../assets/js/bookings.js"></script>  
  </body>
</html>