<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
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

    
    <main class="pt-16 min-h-screen flex items-center justify-center px-4 py-8">
        <div class="max-w-5xl w-full flex shadow-lg rounded-lg overflow-hidden">
            <div class="w-1/2 bg-white p-8">
                <div id="signupForm">
                    <h2 class="text-2xl font-serif text-center mb-8">Create Profile</h2>
                    <form id="signup-form" class="space-y-6" nonvalidate>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="first-name">First Name</label>
                            <input type="text" id="first-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                            <div id="first-name-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="last-name">Last Name</label>
                            <input type="text" id="last-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                            <div id="last-name-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="email">Email</label>
                            <input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                            <div id="email-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="password">Password</label>
                            <div class="relative">
                                <input type="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700" onclick="togglePassword(this)">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <div id="password-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="confirm-password">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700" onclick="togglePassword(this)">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <div id="confirm-password-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <button type="submit" class="w-full bg-black text-white uppercase tracking-widest py-2 hover:bg-gray-800 transition">Sign Up</button>
                        <div class="text-center text-sm">
                            Already have an account? 
                            <a href="/view/login.html" class="text-gray-800 hover:text-zinc-500">Login</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="w-1/2 bg-gray-100 hidden md:block">
                <img src="../../assets/images/christian-lambert-vmIWr0NnpCQ-unsplash.jpg" alt="Background Image" class="w-full h-full object-cover">
            </div>
        </div>
    </main>

    <script src="../../assets/js/signup.js"></script>
</body>
</html>