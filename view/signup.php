<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile - Phantom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');

        .playfair {
            font-family: 'Playfair Display', serif;
        }

        /* Custom scrollbar for form section */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E0 #F1F5F9;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F1F5F9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #CBD5E0;
            border-radius: 3px;
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
    </style>
</head>

<body class="h-screen bg-gray-50">
    <div class="h-screen flex">
        <!-- Form Section - Scrollable -->
        <div class="w-full md:w-1/2 overflow-y-auto custom-scrollbar p-6 md:p-12 bg-white">
            <div class="max-w-lg mx-auto">
                <h2 class="text-3xl font-bold mb-8 playfair">Create Profile</h2>

                <form id="signup-form" class="space-y-6" novalidate>
                    <!-- First Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="first-name">First Name</label>
                        <input type="text" id="first-name"
                            class="input-field w-full px-4 py-3 rounded-md"
                            placeholder="Enter your first name">
                        <div id="first-name-error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="last-name">Last Name</label>
                        <input type="text" id="last-name"
                            class="input-field w-full px-4 py-3 rounded-md"
                            placeholder="Enter your last name">
                        <div id="last-name-error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="email">Email</label>
                        <input type="email" id="email"
                            class="input-field w-full px-4 py-3 rounded-md"
                            placeholder="Enter your email address">
                        <div id="email-error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="phone">Phone Number</label>
                        <input type="tel" id="phone"
                            class="input-field w-full px-4 py-3 rounded-md"
                            placeholder="+1234567890">
                        <div id="phone-error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="password">Password</label>
                        <div class="relative">
                            <input type="password" id="password"
                                class="input-field w-full px-4 py-3 rounded-md"
                                placeholder="Enter your password">
                            <button type="button" id="togglePasswordBtn"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <div id="password-error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="confirm-password">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirm-password"
                                class="input-field w-full px-4 py-3 rounded-md"
                                placeholder="Confirm your password">
                            <button type="button" id="togglePasswordConfirmBtn"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <div id="confirm-password-error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Account Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="user-type">Account Type</label>
                        <select id="user-type"
                            class="input-field w-full px-4 py-3 rounded-md appearance-none">
                            <option value="guest">Guest</option>
                            <option value="owner">Hotel Owner</option>
                        </select>
                        <div id="user-type-error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-black text-white py-3 rounded-md hover:bg-gray-800 transition duration-300">
                        Sign Up
                    </button>

                    <!-- Login Link -->
                    <div class="text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="login.php" class="text-black font-medium hover:underline">Login</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Image Section - Hidden on mobile -->
        <div class="hidden md:block md:w-1/2 relative">
            <img src="../assets/images/christian-lambert-vmIWr0NnpCQ-unsplash.jpg"
                alt="Luxury Hotel"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <div class="text-white text-center p-8">
                    <h2 class="text-4xl font-bold mb-4 playfair">Join Phantom</h2>
                    <p class="text-xl">Experience luxury like never before</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/signup.js"></script>
</body>

</html>