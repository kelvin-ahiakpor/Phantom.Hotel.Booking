<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Hotel | Phantom</title>
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
                    <i class="far fa-heart"></i>
                </button>
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="far fa-user"></i>
                </button>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 pt-20">
        <!-- Hotel Creation Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-6">
            <!-- Left Column: Hotel Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-3xl font-serif mb-4">Create Your Hotel</h2>
                    <form id="hotelForm" novalidate>
                        <!-- Hotel Name & Location -->
                        <div class="mb-4">
                            <label for="hotelName" class="block text-sm font-medium text-gray-700">Hotel Name</label>
                            <input type="text" id="hotelName" name="hotelName" required class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter hotel name">
                        </div>
                        <div class="mb-4">
                            <label for="hotelLocation" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" id="hotelLocation" name="hotelLocation" required class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter hotel location">
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="hotelDescription" class="block text-sm font-medium text-gray-700">Hotel Description</label>
                            <textarea id="hotelDescription" name="hotelDescription" rows="4" required class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe your hotel"></textarea>
                        </div>

                        <!-- Hotel Images Upload -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hotel Images</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="file" name="hotelImage1" id="hotelImage1" class="p-2 border rounded-lg" onchange="updateImagePreview()">
                                <input type="file" name="hotelImage2" id="hotelImage2" class="p-2 border rounded-lg" onchange="updateImagePreview()">
                                <input type="file" name="hotelImage3" id="hotelImage3" class="p-2 border rounded-lg" onchange="updateImagePreview()">
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Amenities</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="wifi" name="wifi" class="mr-2">
                                    <label for="wifi">Free WiFi</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="pool" name="pool" class="mr-2">
                                    <label for="pool">Indoor Pool</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="spa" name="spa" class="mr-2">
                                    <label for="spa">Luxury Spa</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="restaurant" name="restaurant" class="mr-2">
                                    <label for="restaurant">Fine Dining</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="valet" name="valet" class="mr-2">
                                    <label for="valet">Valet Parking</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="concierge" name="concierge" class="mr-2">
                                    <label for="concierge">24/7 Concierge</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full px-4 py-2 bg-black text-white hover:bg-gray-900">
                            Create Hotel
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Hotel Images Gallery -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-semibold mb-4">Hotel Image Preview</h2>
                    <div class="grid grid-cols-4 gap-4 mb-8" id="imagePreview">
                        <div class="col-span-2 row-span-2">
                            <img id="previewImage1" src="/assets/images/jude-wilson-qeiqU0f7TyE-unsplash.jpg" alt="Hotel Preview" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div>
                            <img id="previewImage2" src="/assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg" alt="Hotel Room" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div>
                            <img id="previewImage3" src="/assets/images/crew-szCvt1gP2d4-unsplash.jpg" alt="Hotel Pool" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div>
                            <img id="previewImage4" src="/assets/images/bilderboken-rlwE8f8anOc-unsplash.jpg" alt="Hotel Restaurant" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div>
                            <img id="previewImage5" src="/assets/images/engin-akyurt-SMwCQZWayj0-unsplash.jpg" alt="Hotel Spa" class="w-full h-full object-cover rounded-lg">
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">These images will be shown on your hotel page once the creation is complete. Ensure they represent your hotel accurately!</p>
                </div>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="mt-12 py-6 bg-white border-t">
        <div class="container mx-auto text-center text-gray-500 text-sm">
            &copy; 2024 Phantom Hotel Management. All rights reserved.
        </div>
    </footer>

    <!-- Link to external JavaScript file -->
    <script src="/assets/js/create_hotel.js"></script>
</body>
</html>