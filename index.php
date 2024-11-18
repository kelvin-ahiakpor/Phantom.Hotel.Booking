<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phantom Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lucide-icons@0.263.1/dist/lucide.min.js"></script>

    <style>
        .slide-transition {
            transition: transform 0.5s ease-in-out;
        }
        
        .destination-card {
            transition: opacity 0.3s ease-in-out;
        }
        
        .destination-card:hover {
            opacity: 0.95;
        }

        .indicator.active {
            width: 2rem;
            background-color: #000;
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen">
    <!-- Header -->
    <header class="bg-white py-4 px-8 flex justify-between items-center sm:py-6 sm:px-12">
        <div class="text-2xl text-black font-bold">Phantom</div>
        <nav class="flex items-center space-x-6 sm:space-x-8">
            <div>
                <select class="bg-transparent p-1 rounded-lg text-sm font-serif text-black uppercase tracking-widest sm:text-base">
                    <option>en</option>
                    <option>de</option>
                    <option>fr</option>
                    <option>ru</option>
                    <option>ar</option>
                    <option>es</option>
                </select>
            </div>
            <a href="/view/login.html" class="text-black hover:text-black">Sign in</a>
            <a href="/view/hotel_feed.html" class="border border-black text-black bg-white font-serif uppercase tracking-widest hover:bg-black hover:text-white transition sm:px-5 sm:py-1.5">Book Now</a>
        </nav>
    </header>

    <header class="bg-white py-3 px-8 flex justify-between items-center">
        <div class="flex space-x-8">
            <a href="#" class="text-lg text-black font-serif relative group">
                Hotels & Resorts
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-black transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="#" class="text-lg text-black font-serif relative group">
                Residences
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-black transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="#" class="text-lg text-black font-serif relative group">
                About Us
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-black transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="#" class="text-lg text-black font-serif relative group">
                The Journey
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-black transition-all duration-300 group-hover:w-full"></span>
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex flex-col justify-center items-center h-[calc(100vh-132px)]">
        <section class="relative w-full h-full">
            <img src="/assets/images/emediong-umoh-PCYxROYCe6k-unsplash.jpg" alt="Luxury interior" class="w-full h-full object-cover" />
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black bg-opacity-50 p-8 text-center max-w-2xl sm:p-12 sm:max-w-3xl">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 sm:mb-6">Discover Your Perfect Stay</h1>
                <p class="text-xl md:text-2xl mb-8 sm:mb-10">
                    Experience unparalleled luxury with our curated collection of elite accommodations.
                </p>
                <div class="flex space-x-4 justify-center sm:space-x-6">
                    <a href="/view/hotel_feed.html" class="bg-white text-black px-6 py-2 hover:bg-gray-200 transition sm:px-8 sm:py-3">Book Now</a>
                    <button class="border border-white px-6 py-2 hover:bg-white hover:text-black transition sm:px-8 sm:py-3">How We Work</button>
                </div>
            </div>
        </section>
    </main>

    <!-- Curated Luxury Experiences -->
    <section class="w-full bg-gradient-to-b from-gray-100 to-gray-200 py-20 sm:py-24">
        <div class="container mx-auto px-4 sm:px-8">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-serif text-gray-900 mb-4">Curated Luxury Experiences</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-yellow-600 mx-auto"></div>
                <p class="text-gray-600 mt-6 text-lg font-light max-w-2xl mx-auto">Discover our collection of the world's most distinguished accommodations, where exceptional service meets unparalleled elegance.</p>
            </div>
    
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10">
                <!-- Hotel Card 1 -->
                <div class="group bg-white shadow-xl overflow-hidden transform transition-transform duration-300 hover:-translate-y-2">
                    <div class="relative">
                        <img src="/assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg" alt="Hotel Majestic Paris" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 right-4 bg-black bg-opacity-60 px-4 py-2 rounded-full">
                            <span class="text-yellow-400 font-serif">★ 9.2</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-serif text-gray-900 mb-2">Hotel Majestic</h3>
                        <p class="text-gray-500 mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Paris, France
                        </p>
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-sm text-gray-600">Exceptional Guest Rating</div>
                            <div class="text-sm text-gray-500">2,345 reviews</div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <a href="#" class="block text-center bg-black text-white px-6 py-3 hover:bg-gray-900 transition-colors duration-300">
                                Reserve Suite
                            </a>
                        </div>
                    </div>
                </div>
    
                <!-- Hotel Card 2 -->
                <div class="group bg-white shadow-xl overflow-hidden transform transition-transform duration-300 hover:-translate-y-2">
                    <div class="relative">
                        <img src="/assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg" alt="Ritz-Carlton New York" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 right-4 bg-black bg-opacity-60 px-4 py-2 rounded-full">
                            <span class="text-yellow-400 font-serif">★ 9.5</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-serif text-gray-900 mb-2">The Ritz-Carlton</h3>
                        <p class="text-gray-500 mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            New York, USA
                        </p>
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-sm text-gray-600">Exceptional Guest Rating</div>
                            <div class="text-sm text-gray-500">4,567 reviews</div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <a href="#" class="block text-center bg-black text-white px-6 py-3 hover:bg-gray-900 transition-colors duration-300">
                                Reserve Suite
                            </a>
                        </div>
                    </div>
                </div>
    
                <!-- Hotel Card 3 -->
                <div class="group bg-white shadow-xl overflow-hidden transform transition-transform duration-300 hover:-translate-y-2">
                    <div class="relative">
                        <img src="/assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg" alt="Burj Al Arab Dubai" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 right-4 bg-black bg-opacity-60 px-4 py-2 rounded-full">
                            <span class="text-yellow-400 font-serif">★ 9.8</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-serif text-gray-900 mb-2">Burj Al Arab</h3>
                        <p class="text-gray-500 mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Dubai, UAE
                        </p>
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-sm text-gray-600">Exceptional Guest Rating</div>
                            <div class="text-sm text-gray-500">7,890 reviews</div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <a href="#" class="block text-center bg-black text-white px-6 py-3  hover:bg-gray-900 transition-colors duration-300">
                                Reserve Suite
                            </a>
                        </div>
                    </div>
                </div>
    
                <!-- Hotel Card 4 -->
                <div class="group bg-white shadow-xl overflow-hidden transform transition-transform duration-300 hover:-translate-y-2">
                    <div class="relative">
                        <img src="/assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg" alt="Four Seasons Bora Bora" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 right-4 bg-black bg-opacity-60 px-4 py-2 rounded-full">
                            <span class="text-yellow-400 font-serif">★ 9.4</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-serif text-gray-900 mb-2">Four Seasons</h3>
                        <p class="text-gray-500 mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Bora Bora, French Polynesia
                        </p>
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-sm text-gray-600">Exceptional Guest Rating</div>
                            <div class="text-sm text-gray-500">3,456 reviews</div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <a href="#" class="block text-center bg-black text-white px-6 py-3 hover:bg-gray-900 transition-colors duration-300">
                                Reserve Suite
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fixed Trending Destinations section -->
    <section class="py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-5xl font-serif text-gray-900 mb-4">Curated Destinations</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-yellow-600 mx-auto"></div>
                <p class="text-gray-600 text-xl font-light max-w-3xl mx-auto">
                    Discover our handpicked collection of the world's most prestigious destinations, 
                    where extraordinary experiences await the discerning traveler
                </p>
            </div>

            <div class="relative max-w-6xl mx-auto">
                <div id="slider" class="overflow-hidden rounded-xl shadow-2xl">
                    <div id="slides-container" class="flex slide-transition">
                        
                    </div>
                </div>

                <!-- Navigation arrows -->
                <button onclick="prevSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-30 hover:bg-opacity-50 text-white p-4 rounded-full backdrop-blur-sm transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <button onclick="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-30 hover:bg-opacity-50 text-white p-4 rounded-full backdrop-blur-sm transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div id="indicators" class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex items-center space-x-3">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-black  py-4 px-8 text-center">
        <div class="flex justify-center space-x-4 mb-2">
            <div class="cursor-pointer"><i data-lucide="facebook" class="w-5 h-5"></i></div>
            <div class="cursor-pointer"><i data-lucide="twitter" class="w-5 h-5"></i></div>
            <div class="cursor-pointer"><i data-lucide="instagram" class="w-5 h-5"></i></div>
            <div class="cursor-pointer"><i data-lucide="linkedin" class="w-5 h-5"></i></div>
        </div>
        <p class="text-sm">&copy; 2024 Phantom Luxury Accommodations. All Rights Reserved.</p>
        <p class="text-sm">14 White Hart Lane, Opulent City, OC1 2LD</p>
    </footer>
<script src="/assets/js/index.js"></script>
</body>
</html>