<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup</title>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.460.0/dist/umd/lucide.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script> -->
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
                    <form id="signup-form" class="space-y-6" novalidate>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="first-name">First Name</label>
                            <input type="text" id="first-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div id="first-name-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="last-name">Last Name</label>
                            <input type="text" id="last-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div id="last-name-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="email">Email</label>
                            <input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div id="email-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <!-- Add this after the email input field -->
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="mt-1">
                                <input type="tel" id="phone" name="phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black"
                                    placeholder="+1234567890">
                                <div id="phone-error" class="text-red-500 text-sm mt-1"></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="password">Password</label>
                            <div class="relative">
                                <input type="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p id="password-error" class="text-red-500 text-sm mt-1"></p>
                                <button type="button" id="togglePasswordBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="confirm-password">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="button" id="togglePasswordConfirmBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <div id="confirm-password-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <!-- // use type section to select the user type, either guest or owner, by default everyone is a guest -->
                        <!-- Add this right before the submit button in your form -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="user-type">Account Type</label>
                            <select id="user-type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="guest">Guest</option>
                                <option value="owner">Hotel Owner</option>
                            </select>
                            <div id="user-type-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <button type="submit" class="w-full bg-black text-white uppercase tracking-widest py-2 hover:bg-gray-800 transition">Sign Up</button>
                        <div class="text-center text-sm">
                            Already have an account?
                            <a href="../view/login.php" class="text-blue-500 hover:text-blue-400">Login</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="w-1/2 bg-gray-100 hidden md:block">
                <img src="../assets/images/christian-lambert-vmIWr0NnpCQ-unsplash.jpg" alt="Background Image" class="w-full h-full object-cover">
            </div>
        </div>
    </main>

    <script src="../assets/js/signup.js"></script>
</body>

</html>