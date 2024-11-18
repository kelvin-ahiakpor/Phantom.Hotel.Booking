<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phantom Booking</title>
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
        <!-- Hotel Images Gallery -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="col-span-2 row-span-2">
                <img src="/assets/images/jude-wilson-qeiqU0f7TyE-unsplash.jpg" alt="Main Hotel View" class="w-full h-full object-cover rounded-lg">
            </div>
            <div>
                <img src="/assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg" alt="Hotel Room" class="w-full h-full object-cover rounded-lg">
            </div>
            <div>
                <img src="/assets/images/crew-szCvt1gP2d4-unsplash.jpg" alt="Hotel Pool" class="w-full h-full object-cover rounded-lg">
            </div>
            <div>
                <img src="/assets/images/bilderboken-rlwE8f8anOc-unsplash.jpg" alt="Hotel Restaurant" class="w-full h-full object-cover rounded-lg">
            </div>
            <div>
                <img src="/assets/images/engin-akyurt-SMwCQZWayj0-unsplash.jpg" alt="Hotel Spa" class="w-full h-full object-cover rounded-lg">
            </div>
        </div>

        <!-- Hotel Info & Booking Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Hotel Information -->
            <div class="lg:col-span-2">
                <!-- Hotel Header -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-3xl font-serif mb-2">The Hampton Inn</h1>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                15 Place Vendôme, 75001 Paris, France
                            </p>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="ml-1 font-semibold">4.9</span>
                                <span class="text-gray-600 ml-1">(524 reviews)</span>
                            </div>
                            <button class="flex items-center text-gray-600 hover:text-red-500">
                                <i class="far fa-heart mr-1"></i>
                                Save to Wishlist
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">About This Hotel</h2>
                    <p class="text-gray-600 mb-4">
                        Experience unparalleled luxury at The Ritz-Carlton Paris, where timeless elegance meets modern sophistication. Located in the heart of the city on Place Vendôme, our historic hotel offers a truly memorable stay with breathtaking views of the Paris skyline.
                    </p>
                    <p class="text-gray-600 mb-4">
                        The hotel features an award-winning spa, michelin-starred restaurants, and personalized service that exceeds every expectation. Each room and suite is uniquely decorated with elegant furnishings and premium amenities.
                    </p>
                </div>

                <!-- Amenities -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Amenities</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-wifi mr-2 text-gray-600"></i>
                            <span>Free WiFi</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-swimming-pool mr-2 text-gray-600"></i>
                            <span>Indoor Pool</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-spa mr-2 text-gray-600"></i>
                            <span>Luxury Spa</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-utensils mr-2 text-gray-600"></i>
                            <span>Fine Dining</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-car mr-2 text-gray-600"></i>
                            <span>Valet Parking</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-concierge-bell mr-2 text-gray-600"></i>
                            <span>24/7 Concierge</span>
                        </div>
                    </div>
                </div>

                <!-- Available Rooms -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Available Rooms</h2>
                    
                    <!-- Deluxe Room -->
                    <div class="border-b pb-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <img src="/assets/images/frames-for-your-heart-FqqiAvJejto-unsplash.jpg" alt="Deluxe Room" class="w-full h-48 object-cover rounded-lg">
                            <div class="md:col-span-2">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold">Deluxe Room</h3>
                                    <p class="text-xl font-bold">€850<span class="text-sm font-normal text-gray-600">/night</span></p>
                                </div>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-sm">King Bed</span>
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-sm">45 m²</span>
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-sm">City View</span>
                                </div>
                                <p class="text-gray-600 mb-4">Elegant room featuring classic French décor, marble bathroom, and stunning city views.</p>
                                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                                    Select Room
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Suite -->
                    <div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <img src="/assets/images/linus-mimietz-p3UWyaujtQo-unsplash.jpg" alt="Suite" class="w-full h-48 object-cover rounded-lg">
                            <div class="md:col-span-2">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold">Vendôme Suite</h3>
                                    <p class="text-xl font-bold">€1,500<span class="text-sm font-normal text-gray-600">/night</span></p>
                                </div>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-sm">King Bed</span>
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-sm">75 m²</span>
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-sm">Balcony</span>
                                </div>
                                <p class="text-gray-600 mb-4">Luxurious suite with separate living area, private balcony, and stunning views of Place Vendôme.</p>
                                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                                    Select Room
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Widget -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-semibold mb-4">Book Your Stay</h2>
                    <form nonvalidate>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                            <input type="date" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                            <input type="date" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guests</label>
                            <select class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>1 Guest</option>
                                <option>2 Guests</option>
                                <option>3 Guests</option>
                                <option>4 Guests</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Special Requests</label>
                            <textarea class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
                        </div>
                        <button class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 mb-4">
                            Check Availability
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>