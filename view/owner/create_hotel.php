<?php
session_start();
require "../middleware/checkInternetConnection.php";
checkInternetConnection();
require_once '../../db/config.php';
require_once '../../middleware/checkUserAccess.php';
checkUserAccess('owner');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Hotel | Phantom</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/create_hotel.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm fixed w-full z-10">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="text-2xl font-serif text-gray-800">Phantom</div>
            <div class="flex items-center space-x-4">
                <button id="profileBtn" class="text-gray-600 hover:text-gray-800">
                    <i class="far fa-user"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Profile Modal -->
    <div id="profileModal" class="hidden fixed top-14 right-4 bg-white rounded-lg shadow-lg p-6 z-20">
        <div class="flex flex-col space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium"><?php echo htmlspecialchars($_SESSION['firstName']); ?></h3>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                </div>
                <button id="closeProfileModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <hr>
            <a href="manage_account.php" class="text-blue-600 hover:text-blue-700">Manage Account</a>
            <a href="../../actions/logout.php" class="text-red-600 hover:text-red-700">Logout</a>
        </div>
    </div>

    <main class="container mx-auto px-4 pt-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-3xl font-serif mb-4">Create Your Hotel</h2>
                    <form id="hotelForm" class="space-y-6" novalidate>
                        <!-- Hotel Name -->
                        <div>
                            <label for="hotelName" class="block text-sm font-medium text-gray-700">Hotel Name</label>
                            <input type="text" id="hotelName" name="hotelName" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black"
                                placeholder="Enter hotel name">
                            <div id="hotelNameError" class="error-text"></div>
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="hotelLocation" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" id="hotelLocation" name="hotelLocation" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black"
                                placeholder="Enter hotel location">
                            <div id="hotelLocationError" class="error-text"></div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="hotelDescription" class="block text-sm font-medium text-gray-700">Hotel Description</label>
                            <textarea id="hotelDescription" name="hotelDescription" rows="4" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black"
                                placeholder="Describe your hotel"></textarea>
                            <div id="hotelDescriptionError" class="error-text"></div>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hotel Images</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="image-input-wrapper">
                                    <input type="file" id="hotelImage1" name="hotelImage1" accept="image/jpeg,image/png" class="hidden">
                                    <img id="preview1" class="preview-image" alt="Preview 1">
                                    <div class="placeholder-text">
                                        <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                        <p>Click to upload main image</p>
                                    </div>
                                </div>
                                <div class="image-input-wrapper">
                                    <input type="file" id="hotelImage2" name="hotelImage2" accept="image/jpeg,image/png" class="hidden">
                                    <img id="preview2" class="preview-image" alt="Preview 2">
                                    <div class="placeholder-text">
                                        <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                        <p>Additional image</p>
                                    </div>
                                </div>
                                <div class="image-input-wrapper">
                                    <input type="file" id="hotelImage3" name="hotelImage3" accept="image/jpeg,image/png" class="hidden">
                                    <img id="preview3" class="preview-image" alt="Preview 3">
                                    <div class="placeholder-text">
                                        <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                        <p>Additional image</p>
                                    </div>
                                </div>
                            </div>
                            <div id="imageError" class="error-text mt-2"></div>
                        </div>

                        <!-- Amenities -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Amenities</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="wifi" id="wifi" class="rounded border-gray-300 text-black focus:ring-black">
                                    <span>Free WiFi</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="pool" id="pool" class="rounded border-gray-300 text-black focus:ring-black">
                                    <span>Indoor Pool</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="spa" id="spa" class="rounded border-gray-300 text-black focus:ring-black">
                                    <span>Luxury Spa</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="restaurant" id="restaurant" class="rounded border-gray-300 text-black focus:ring-black">
                                    <span>Fine Dining</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="valet" id="valet" class="rounded border-gray-300 text-black focus:ring-black">
                                    <span>Valet Parking</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="concierge" id="concierge" class="rounded border-gray-300 text-black focus:ring-black">
                                    <span>24/7 Concierge</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-black text-white py-2 px-4 rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors">
                            <span>Create Hotel</span>
                            <div class="loading-spinner">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Live Preview Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                    <h2 class="text-lg font-semibold mb-4">Preview</h2>
                    <div class="space-y-4">
                        <!-- Image Preview -->
                        <div class="aspect-w-16 aspect-h-9 mb-4">
                            <img id="previewMainImage" src="../../assets/images/default-hotel.png" alt="Hotel Preview" class="w-full h-48 object-cover rounded-lg">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <img id="previewImage2" src="../../assets/images/default-hotel.png" alt="Hotel Preview" class="w-full h-24 object-cover rounded-lg">
                            <img id="previewImage3" src="../../assets/images/default-hotel.png" alt="Hotel Preview" class="w-full h-24 object-cover rounded-lg">
                        </div>

                        <!-- Hotel Details Preview -->
                        <div class="mt-4">
                            <h3 id="previewName" class="text-xl font-semibold">Hotel Name</h3>
                            <p id="previewLocation" class="text-gray-600">Location</p>
                            <p id="previewDescription" class="text-sm text-gray-500 mt-2">Hotel description will appear here...</p>
                        </div>

                        <!-- Amenities Preview -->
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Amenities</h4>
                            <div class="flex flex-wrap gap-2">
                                <?php
                                $amenityIcons = [
                                    'wifi' => ['icon' => '📶', 'label' => 'Free WiFi'],
                                    'pool' => ['icon' => '🏊', 'label' => 'Indoor Pool'],
                                    'spa' => ['icon' => '💆', 'label' => 'Luxury Spa'],
                                    'restaurant' => ['icon' => '🍽️', 'label' => 'Fine Dining'],
                                    'valet' => ['icon' => '🚗', 'label' => 'Valet Parking'],
                                    'concierge' => ['icon' => '👨‍💼', 'label' => '24/7 Concierge']
                                ];

                                foreach ($amenityIcons as $amenity => $details) {
                                    if (isset($hotelDetails[$amenity]) && $hotelDetails[$amenity]) {
                                        echo '<span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">';
                                        echo $details['icon'] . ' ' . $details['label'];
                                        echo '</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <footer class="mt-12 py-6 bg-white border-t">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; 2024 Phantom Hotel Management. All rights reserved.
        </div>
    </footer>

    <script src="../../assets/js/create_hotel.js"></script>
</body>

</html>