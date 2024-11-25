<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back - Phantom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');

        .playfair {
            font-family: 'Playfair Display', serif;
        }

        .login-container {
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);
        }

        .input-field {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #000;
            box-shadow: none;
            outline: none;
        }

        .login-btn {
            background-color: #000;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #1a1a1a;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <!-- Main Container - Changed w-full for mobile -->
    <div class="login-container bg-white rounded-lg w-full max-w-md md:max-w-6xl flex overflow-hidden">
        <!-- Login Form Section - Full width on mobile -->
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-12 playfair">Welcome back!</h1>

            <form id="login-form" class="space-y-6" novalidate>
                <div>
                    <label for="email" class="block text-sm text-gray-600 mb-2">Email</label>
                    <input type="email"
                        id="email"
                        class="input-field w-full px-4 py-3 rounded-md"
                        placeholder="Enter your email address">
                    <p id="email-error" class="text-red-500 text-sm mt-1"></p>
                </div>

                <div>
                    <label for="password" class="block text-sm text-gray-600 mb-2">Password</label>
                    <div class="relative">
                        <input type="password"
                            id="password"
                            class="input-field w-full px-4 py-3 rounded-md"
                            placeholder="Enter your password">
                        <button type="button"
                            id="togglePasswordBtn"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="password-error" class="text-red-500 text-sm mt-1"></p>
                </div>

                <button type="submit"
                    class="login-btn w-full py-3 text-white font-medium rounded-md">
                    Login
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="signup.php" class="text-black font-medium hover:underline">Create account</a>
                </p>
            </div>
        </div>

        <!-- Image Section - Hidden on mobile (md:block) -->
        <div class="hidden md:block md:w-1/2 relative">
            <img src="../assets/images/hung-li-1HbWj9BDbjE-unsplash.jpg"
                alt="Luxury Hotel Interior"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <div class="text-white text-center p-8">
                    <h2 class="text-4xl font-bold mb-4 playfair">Experience Luxury</h2>
                    <p class="text-xl">Discover the perfect blend of sophistication</p>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="../assets/js/login.js"></script>

</html>