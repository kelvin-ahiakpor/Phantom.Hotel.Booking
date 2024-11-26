<?php
session_start();
require_once '../../db/config.php';
require_once '../../middleware/checkUserAccess.php';
checkUserAccess('admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard | Hotel Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="bg-white shadow-sm fixed w-full z-10">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="text-2xl font-serif text-gray-800">Phantom</div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="far fa-bell"></i>
                </button>
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="far fa-user"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 pt-20">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Dashboard - Guests Overview</h1>

        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <input id="searchInput" type="text" placeholder="Search guest name..." class="w-60 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" oninput="filterGuests()">
                <select id="statusFilter" class="p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="filterGuests()">
                    <option value="">All Guests</option>
                    <option value="Checked In">Checked In</option>
                    <option value="Checked Out">Checked Out</option>
                    <option value="VIP">VIP Guests</option>
                </select>
            </div>
            <button onclick="openAddGuestModal()" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                + Add New Guest
            </button>
        </div>

        <!-- Guest Table -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Profile</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Guest Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Check-out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tbody id="guestTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Guest rows will be dynamically added here -->
                    </tbody>
            </table>
        </div>

        <!-- VIP Guest Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">VIP Guests</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- VIP Guest Card -->
                <div class="bg-gray-100 rounded-lg shadow p-4">
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="../../assets/images/john-doe.avif" alt="VIP Guest Image" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">John Doe</h3>
                            <p class="text-gray-500">Suite 302</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">John has been staying with us since 2022, frequently booking our top suites and availing personalized services.</p>
                    <button class="block text-center bg-black text-white px-6 py-3 hover:bg-gray-900 transition-colors duration-300">Send Greeting</button>
                </div>

                <div class="bg-gray-100 rounded-lg shadow p-4">
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="../../assets/images/emma-jhonson.avif" alt="VIP Guest Image" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Emily Clarke</h3>
                            <p class="text-gray-500">Penthouse Suite</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Emily enjoys exclusive amenities and often attends private events hosted at the hotel. She has been with us since 2021.</p>
                    <button class="block text-center bg-black text-white px-6 py-3 hover:bg-gray-900 transition-colors duration-300">Send Greeting</button>
                </div>
              
            </div>
        </div>

        
    </main>
        <!-- Modals -->
        <div id="guestModal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center z-20">
            <div class="bg-white rounded-lg p-6 w-80">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Guest Details</h2>
                <div id="guestDetails" class="mb-4">
                    <!-- Guest details will be populated here -->
                </div>
                <button onclick="closeGuestModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg w-full">Close</button>
            </div>
        </div>

        <div id="addGuestModal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center z-20">
            <div class="bg-white rounded-lg p-6 w-80">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New Guest</h2>
                <input id="newGuestName" type="text" placeholder="Guest Name" class="w-full p-2 border rounded-lg mb-3" >
                <input id="newGuestRoom" type="text" placeholder="Room" class="w-full p-2 border rounded-lg mb-3" >
                <input id="newGuestCheckIn" type="date" class="w-full p-2 border rounded-lg mb-3" >
                <input id="newGuestCheckOut" type="date" class="w-full p-2 border rounded-lg mb-3" >
                <input type="file" id="newGuestImage" class="w-full p-2 border rounded-lg mb-3" accept="image/*">

                <button onclick="addGuest()" class="px-4 py-2 bg-blue-600 text-white rounded-lg w-full">Add Guest</button>
                <button onclick="closeAddGuestModal()" class="px-4 py-2 mt-2 bg-gray-500 text-white rounded-lg w-full">Cancel</button>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="mt-12 py-6 bg-white border-t">
        <div class="container mx-auto text-center text-gray-500 text-sm">
            &copy; 2024 Phantom Hotel Management. All rights reserved.
        </div>
    </footer>
    <script type="module" src="../../assets/js/dashboard.js"></script>
</body>
</html>


