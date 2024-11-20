<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.460.0/dist/umd/lucide.min.js"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
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

    <main class="pt-16 min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-lg rounded-lg w-full max-w-5xl flex">
            <div class="w-1/2 bg-cover bg-center">
                <img src="../assets/images/sara-dubler-Koei_7yYtIo-unsplash.jpg" alt="Background Image" class="w-full h-full object-cover">
            </div>
            <div class="w-1/2 p-8">
                <h1 class="text-3xl font-serif mb-6 text-center"> Sign in </h1>
                <form id="login-form" class="space-y-6" nonvalidate>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p id="email-error" class="text-red-500 text-sm mt-1"></p>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p id="password-error" class="text-red-500 text-sm mt-1"></p>
                            <button type="button" id="togglePasswordBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-black text-white uppercase tracking-widest py-2 hover:bg-gray-800 transition">Login</button>
                </form>
                <div class="text-center text-sm py-8">
                    Don't have an account? <a href="../view/signup.php" class="text-gray-800 hover:text-zinc-500">Sign up</a>
                </div>
            </div>
        </div>
    </main>
    <script src="../assets/js/login.js"></script> 
</body>
</html>