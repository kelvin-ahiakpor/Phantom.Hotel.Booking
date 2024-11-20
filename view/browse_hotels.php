<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Carousel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black min-h-screen">
    <!-- Background Image -->
    <div class="fixed inset-0 z-0">
        <img src="../assets/images/sara-dubler-Koei_7yYtIo-unsplash.jpg" alt="Background" class="w-full h-full object-cover opacity-30">
    </div>

    <div id="carousel-container" class="relative w-full h-screen overflow-hidden flex items-center justify-center">
        <!-- Navigation Buttons -->
        <button id="prevBtn" class="absolute left-8 z-50 text-white hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button id="nextBtn" class="absolute right-8 z-50 text-white hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Hotel Cards Container -->
        <div id="carousel" class="relative flex items-center justify-center gap-4">
        </div>

        <!-- Pagination -->
        <div class="absolute bottom-8 left-0 right-0 flex justify-center items-center gap-2">
            <span id="current-slide" class="text-white text-lg font-light">2</span>
            <span class="text-white text-lg font-light">/</span>
            <span id="total-slides" class="text-white text-lg font-light">9</span>
        </div>
    </div>
    <script src="../assets/js/browse_hotels.js"></script>
</body>
</html>