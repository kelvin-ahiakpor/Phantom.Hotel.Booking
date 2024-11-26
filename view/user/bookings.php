<?php
require "../../functions/session_check.php";
require '../../db/config.php';
require_once '../../middleware/checkUserAccess.php';
checkUserAccess('guest');

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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <link
    href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css"
    rel="stylesheet" />
</head>





<body class="bg-gray-100 min-h-screen">
  <!-- Profile Modal -->
  <div id="profileModal" class="hidden fixed top-14 right-4 bg-white rounded-lg shadow-lg p-6 z-20">
    <div class="flex flex-col space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-medium"><?php echo htmlspecialchars($_SESSION['firstName']); ?></h3>
          <p class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
        </div>

        <button id="closeProfileModal" class="text-gray-400 hover:text-gray-500">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <hr>
      <a href="../manage_account.php" class="text-blue-600 hover:text-blue-700">Manage Account</a>
      <a href="../../actions/logout.php" class="text-red-600 hover:text-red-700">Logout</a>
    </div>
  </div>

  <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-20">
        <div class="flex items-center">
          <h1 class="text-3xl font-serif font-bold text-gray-800">Phantom</h1>
        </div>
        <div class="flex items-center space-x-4">
          <button id="profileBtn" class="text-gray-600 hover:text-gray-800">
            <i class="far fa-user"></i>
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
          onclick="window.location.href='new_booking.php';">
          New Booking
        </button>
      </div>
    </div>

    <div
      id="bookings-container"
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>
  </main>
  <script type="module" src="../../assets/js/bookings.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const profileBtn = document.getElementById('profileBtn');
      const signOutBtn = document.getElementById('signOutBtn');
      const profileModal = document.getElementById('profileModal');
      const closeModal = document.getElementById('close-modal');

      profileBtn.addEventListener('click', () => {
        profileModal.classList.remove('hidden');
      });

      closeModal.addEventListener('click', () => {
        profileModal.classList.add('hidden');
      });

      window.addEventListener('click', (e) => {
        if (!profileModal.contains(e.target) && !profileBtn.contains(e.target)) {
          profileModal.classList.add('hidden');
        }
      });

      closeModal.addEventListener('click', () => {
        profileModal.classList.add('hidden');
      });
    });
  </script>
</body>

</html>