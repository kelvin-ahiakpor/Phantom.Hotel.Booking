<?php
session_start();
require_once '../../db/config.php';
require_once '../../middleware/checkUserAccess.php';
checkUserAccess('owner');

try {
    // Get hotel details for the logged-in owner
    $ownerId = $_SESSION['userId'];
    $stmt = $conn->prepare("
        SELECT h.*
        FROM hb_hotels h
        WHERE h.owner_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $ownerId);
    $stmt->execute();
    $hotelDetails = $stmt->get_result()->fetch_assoc();

    if (!$hotelDetails) {
        header("Location: manage_hotel.php");
        exit;
    }

    // Get hotel images
    $imageStmt = $conn->prepare("
        SELECT image_url 
        FROM hb_hotel_images 
        WHERE hotel_id = ?
        ORDER BY image_url ASC
        LIMIT 3
    ");
    $imageStmt->bind_param("i", $hotelDetails['hotel_id']);
    $imageStmt->execute();
    $imageResult = $imageStmt->get_result();
    $hotelImages = [];
    while ($image = $imageResult->fetch_assoc()) {
        $hotelImages[] = $image['image_url'];
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    header("Location: manage_hotel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hotel | Phantom</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .image-input-wrapper {
            position: relative;
            width: 100%;
            height: 150px;
            border: 2px dashed #cbd5e0;
            border-radius: 0.5rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .image-input-wrapper input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .image-input-wrapper:hover {
            border-color: #4a5568;
        }

        .preview-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .loading-spinner {
            display: none;
        }

        .error-text {
            color: #e53e3e;
            font-size: 0.875rem;
            display: none;
        }
    </style>
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm fixed w-full z-10">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="manage_hotel.php" class="text-2xl font-serif text-gray-800">Phantom</a>
            <div class="flex items-center space-x-4">
                <button id="profileBtn" class="text-gray-600 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Profile Modal -->
    <div id="profileModal" class="hidden fixed top-14 right-4 bg-white rounded-lg shadow-lg p-6 z-20">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-medium"><?php echo htmlspecialchars($_SESSION['firstName']); ?></h3>
            <button id="closeProfileModal" class="text-gray-400 hover:text-gray-500">×</button>
        </div>
        <div class="space-y-2">
            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <a href="../../actions/logout.php" class="block text-red-600 hover:text-red-700">Logout</a>
        </div>
    </div>

    <main class="container mx-auto px-4 pt-20 pb-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold">Edit Hotel</h1>
                    <a href="manage_hotel.php" class="text-gray-600 hover:text-gray-800">
                        ← Back to Dashboard
                    </a>
                </div>

                <form id="editHotelForm" class="space-y-6">
                    <input type="hidden" name="hotelId" value="<?php echo htmlspecialchars($hotelDetails['hotel_id']); ?>">

                    <!-- Hotel Name -->
                    <div>
                        <label for="hotelName" class="block text-sm font-medium text-gray-700">Hotel Name</label>
                        <input type="text" id="hotelName" name="hotelName"
                            value="<?php echo htmlspecialchars($hotelDetails['hotel_name']); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                            required>
                        <div id="hotelNameError" class="error-text"></div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" id="location" name="location"
                            value="<?php echo htmlspecialchars($hotelDetails['location']); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                            required>
                        <div id="locationError" class="error-text"></div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" id="address" name="address"
                            value="<?php echo htmlspecialchars($hotelDetails['address']); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                            required>
                        <div id="addressError" class="error-text"></div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                            required><?php echo htmlspecialchars($hotelDetails['description']); ?></textarea>
                        <div id="descriptionError" class="error-text"></div>
                    </div>

                    <!-- Price per Night -->
                    <div>
                        <label for="pricePerNight" class="block text-sm font-medium text-gray-700">Price per Night ($)</label>
                        <input type="number" id="pricePerNight" name="pricePerNight"
                            value="<?php echo htmlspecialchars($hotelDetails['price_per_night']); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                            min="0" step="0.01" required>
                        <div id="priceError" class="error-text"></div>
                    </div>

                    <!-- Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hotel Images</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="image-input-wrapper">
                                    <input type="file"
                                        id="hotelImage<?php echo $i + 1; ?>"
                                        name="hotelImage<?php echo $i + 1; ?>"
                                        accept="image/jpeg,image/png"
                                        class="hidden">
                                    <?php if (isset($hotelImages[$i])): ?>
                                        <img src="../../<?php echo htmlspecialchars($hotelImages[$i]); ?>"
                                            class="preview-image"
                                            id="preview<?php echo $i + 1; ?>"
                                            alt="Hotel image <?php echo $i + 1; ?>">
                                    <?php else: ?>
                                        <img id="preview<?php echo $i + 1; ?>"
                                            class="preview-image"
                                            style="display: none;">
                                        <div class="placeholder-text">
                                            <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span class="text-sm text-gray-500">
                                                <?php echo $i === 0 ? 'Main Image' : 'Additional Image'; ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div id="imageError" class="error-text mt-2"></div>
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" id="availability" name="availability"
                                class="rounded border-gray-300 text-black focus:ring-black"
                                <?php echo $hotelDetails['availability'] ? 'checked' : ''; ?>>
                            <span class="text-sm font-medium text-gray-700">Hotel is Available for Booking</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="manage_hotel.php" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded-md hover:bg-gray-800 transition flex items-center">
                            <span>Save Changes</span>
                            <div class="loading-spinner ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="../../assets/js/edit_hotel.js"></script>
</body>

</html>