<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phantom - Luxury Hotel Experience</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/index.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="fixed w-full z-50 px-6 py-6 transition-all duration-300" id="navbar">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold text-white playfair">PHANTOM</div>

            <!-- Hamburger Menu Button -->
            <div class="md:hidden">
                <button class="hamburger" id="menuBtn" aria-label="Menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
            <div class="hidden md:flex space-x-4">
                <a href="./view/login.php" class="px-6 py-2 text-white border border-white rounded-full hover:bg-white hover:text-black transition duration-300">
                    Sign In
                </a>
                <a href="./view/signup.php" class="px-6 py-2 bg-white text-black rounded-full hover:bg-gray-100 transition duration-300">
                    Register
                </a>
            </div>
        </div>

        <!-- Overlay -->
        <div class="menu-overlay" id="menuOverlay"></div>

        <div class="mobile-menu">
            <div class="pt-24 px-6">
                <div class="flex flex-col space-y-4 mt-8">
                    <a href="./view/login.php" class="w-full px-6 py-3 text-white border border-white rounded-full hover:bg-white hover:text-black transition duration-300 text-center">
                        Sign In
                    </a>
                    <a href="./view/signup.php" class="w-full px-6 py-3 bg-white text-black rounded-full hover:bg-gray-100 transition duration-300 text-center">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    <div class="carousel">
        <div class="slides" style="background: url('./assets/images/landing_page/landing_page_image.jpg') center/cover;"></div>
        <div class="slides" style="background: url('./assets/images/landing_page/landing_page_image3.webp') center/cover;"></div>
        <div class="slides" style="background: url('./assets/images/landing_page/landing_page_image4.jpg') center/cover;"></div>
        <div class="slides" style="background: url('./assets/images/landing_page/landing_page_image5.jpg') center/cover;"></div>
        <div class="slides" style="background: url('./assets/images/landing_page/landing_page_image6.jpg') center/cover;"></div>
        <div class="slides" style="background: url('./assets/images/landing_page/landing_page_image8.webp') center/cover;"></div>
        <!-- Navigation Arrows -->
        <button class="absolute left-8 top-1/2 transform -translate-y-1/2 z-20 bg-black bg-opacity-50 p-4 rounded-full text-white hover:bg-opacity-75 transition" onclick="changeSlide(-1)">←</button>
        <button class="absolute right-8 top-1/2 transform -translate-y-1/2 z-20 bg-black bg-opacity-50 p-4 rounded-full text-white hover:bg-opacity-75 transition" onclick="changeSlide(1)">→</button>

        <!-- Overlay Content -->
        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-center text-white z-10">
            <h1 style="line-height: 5.5rem;" class="text-6xl md:text-7xl font-bold mb-6 leading-loose text-center kaftan max-w-5xl px-4">
                Experience Luxury Redefined
            </h1>
            <p class="text-xl md:text-2xl mb-12 text-center max-w-2xl px-4">
                Discover the perfect blend of sophistication and comfort with Phantom's curated collection of luxury hotels.
            </p>

            <!-- Restyled Button -->
            <div class="flex space-x-4">
                <a href="./view/user/new_booking.php"
                    class="group relative inline-flex items-center justify-center px-12 py-4 text-lg font-medium tracking-wider">
                    <span class="absolute inset-0 border border-white transition-all duration-300 group-hover:bg-white"></span>
                    <span class="relative text-white transition-colors duration-300 group-hover:text-black">
                        BOOK NOW
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-8 playfair">About Phantom</h2>
                <div class="w-24 h-1 bg-black mx-auto mb-8"></div>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Welcome to Phantom, the ultimate data-powered luxury hotel booking platform that elevates your hospitality experience. At Phantom, we believe that every journey deserves the perfect stay.
                </p>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Our platform seamlessly blends technology with sophistication, offering a streamlined booking process, authentic guest reviews, and curated recommendations tailored to your unique preferences.
                </p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 playfair">Our Team</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="team-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Kelvin Ahiakpor</h3>
                        <p class="text-gray-600 font-medium mb-4">Quality Assurance Lead</p>
                        <p class="text-gray-500 mb-4 text-sm">Responsible for ensuring code quality and functionality through thorough reviews and best practices implementation.</p>
                        <p class="text-gray-400 italic text-sm">Loves philosophy</p>
                        <p class="mt-4 text-sm text-gray-600">Contact: 0505538564</p>
                    </div>
                </div>

                <div class="team-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Bryan Hans-Ampiah</h3>
                        <p class="text-gray-600 font-medium mb-4">Front-end Developer</p>
                        <p class="text-gray-500 mb-4 text-sm">Specializes in creating intuitive, visually appealing, and responsive designs for seamless user experiences.</p>
                        <p class="text-gray-400 italic text-sm">Loves video games</p>
                        <p class="mt-4 text-sm text-gray-600">Contact: 0206444833</p>
                    </div>
                </div>

                <div class="team-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Vera Anthonio</h3>
                        <p class="text-gray-600 font-medium mb-4">Product Manager</p>
                        <p class="text-gray-500 mb-4 text-sm">Oversees product lifecycle and ensures alignment with business goals and customer needs.</p>
                        <p class="text-gray-400 italic text-sm">Loves fashion</p>
                        <p class="mt-4 text-sm text-gray-600">Contact: 0594954506</p>
                    </div>
                </div>

                <div class="team-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Denis Aggyratus Demitrus</h3>
                        <p class="text-gray-600 font-medium mb-4">Back-end Developer</p>
                        <p class="text-gray-500 mb-4 text-sm">Manages server-side logic and ensures robust, secure application functionality.</p>
                        <p class="text-gray-400 italic text-sm">Loves programming</p>
                        <p class="mt-4 text-sm text-gray-600">Contact: 0546508190</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white py-16">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div class="mb-8 md:mb-0">
                    <h3 class="text-3xl font-bold mb-4 playfair">PHANTOM</h3>
                    <p class="text-gray-400">Discover luxury. Discover Phantom.</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400 mb-4">Visit our repository:
                    <a href="https://github.com/kelvin-ahiakpor/Phantom.Hotel.Booking" class="hover:text-white">GitHub</a>
                </p>
                <p class="text-gray-400">&copy; 2024 Phantom. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu functionality
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.querySelector('.mobile-menu');
        const navbar = document.getElementById('navbar');

        menuBtn.addEventListener('click', () => {
            menuBtn.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                menuBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });

        // Your existing carousel code
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slides');
        slides[0].classList.add('active');

        function changeSlide(direction) {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + direction + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        // Auto advance slides
        setInterval(() => changeSlide(1), 5000);

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-black');
            } else {
                navbar.classList.remove('bg-black');
            }
        });
    </script>
</body>

</html>