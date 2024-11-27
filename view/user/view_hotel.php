<?php
require '../../db/config.php';

if (!isset($_GET['hotel_id'])) {
    header('Location: new_booking.php');
    exit;
}

$hotel_id = (int)$_GET['hotel_id'];

// Fetch hotel details with owner information
$hotel_query = "
    SELECT 
        h.*,
        CONCAT(u.first_name, ' ', u.last_name) as owner_name,
        u.email as owner_email,
        u.phone_number as owner_phone
    FROM hb_hotels h
    LEFT JOIN hb_users u ON h.owner_id = u.user_id
    WHERE h.hotel_id = ?
";

$stmt = $conn->prepare($hotel_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$hotel_result = $stmt->get_result();
$hotel = $hotel_result->fetch_assoc();

if (!$hotel) {
    header('Location: new_booking.php');
    exit;
}

// Fetch hotel images
$images_query = "SELECT image_url FROM hb_hotel_images WHERE hotel_id = ?";
$stmt = $conn->prepare($images_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$images_result = $stmt->get_result();
$images = [];
while ($row = $images_result->fetch_assoc()) {
    $images[] = $row['image_url'];
}

// Fetch hotel amenities
$amenities_query = "SELECT * FROM hb_hotel_amenities WHERE hotel_id = ?";
$stmt = $conn->prepare($amenities_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$amenities_result = $stmt->get_result();
$amenities = $amenities_result->fetch_assoc();

// Fetch available rooms
$rooms_query = "
    SELECT * FROM hb_rooms 
    WHERE hotel_id = ? AND availability = 1
    ORDER BY price_per_night ASC
";
$stmt = $conn->prepare($rooms_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$rooms_result = $stmt->get_result();
$rooms = [];
while ($row = $rooms_result->fetch_assoc()) {
    $rooms[] = $row;
}

// Fetch hotel reviews
$reviews_query = "
    SELECT 
        r.*,
        CONCAT(u.first_name, ' ', u.last_name) as reviewer_name
    FROM hb_reviews r
    JOIN hb_users u ON r.user_id = u.user_id
    WHERE r.hotel_id = ?
    ORDER BY r.created_at DESC
";
$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$reviews_result = $stmt->get_result();
$reviews = [];
while ($row = $reviews_result->fetch_assoc()) {
    $reviews[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($hotel['hotel_name']) ?> - Phantom Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .image-gallery {
            scroll-behavior: smooth;
        }

        .amenity-icon {
            width: 24px;
            height: 24px;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-2xl font-serif font-bold text-gray-800">Phantom</h1>
                <button onclick="window.history.back()" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12">
        <!-- Hotel Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-serif font-bold text-gray-900 mb-2">
                <?= htmlspecialchars($hotel['hotel_name']) ?>
            </h1>
            <p class="text-xl text-gray-600 flex items-center">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <?= htmlspecialchars($hotel['location']) ?>
            </p>
        </div>

        <!-- Image Gallery -->
        <div class="mb-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 aspect-[16/9]">
                <!-- TODO: PEEZY fix the file path, anticipated error here -->
                <?php foreach ($images as $index => $image): ?>
                    <div class="<?= $index === 0 ? 'md:col-span-2 md:row-span-2' : '' ?> 
                            rounded-lg overflow-hidden shadow-lg relative group">
                        <img src="../../<?= htmlspecialchars($image) ?>"
                            alt="Hotel Image"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-7 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-5 space-y-8">
                <!-- Description -->
                <section class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-4">About This Hotel</h2>
                    <p class="text-gray-600 leading-relaxed">
                        <?= htmlspecialchars($hotel['description']) ?>
                    </p>
                </section>

                <!-- Amenities -->
                <section class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">Amenities</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <?php if ($amenities): ?>
                            <?php if ($amenities['wifi']): ?>
                                <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50">
                                    <i class="fas fa-wifi text-gray-600"></i>
                                    <span class="font-medium">Free WiFi</span>
                                </div>
                            <?php endif; ?>
                            <?php if ($amenities['pool']): ?>
                                <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50">
                                    <i class="fas fa-swimming-pool text-gray-600"></i>
                                    <span class="font-medium">Swimming Pool</span>
                                </div>
                            <?php endif; ?>
                            <!-- Add other amenities with the same styling -->
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Available Rooms -->
                <section class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">Available Rooms</h2>
                    <div class="space-y-4">
                        <?php if (!empty($rooms)): ?>
                            <?php foreach ($rooms as $room): ?>
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-gray-300 transition-colors">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">
                                                <?= htmlspecialchars($room['room_type']) ?>
                                            </h3>
                                            <p class="text-gray-600 mt-1">
                                                <i class="fas fa-user-friends mr-2"></i>
                                                Up to <?= $room['capacity'] ?> guests
                                            </p>
                                        </div>
                                        <button onclick="window.location.href='booking_form.php?hotel_id=<?= $hotel_id ?>&room_id=<?= $room['room_id'] ?>'"
                                            class="bg-black text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
                                            Book Now
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <p class="text-gray-500">No rooms available at the moment</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Reviews -->
                <!-- TODO: Peezy implement previous guest positing comments ( reviews here ) here -->
                <section class="bg-white rounded-lg shadow-lg p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Guest Reviews</h2>
                        <!-- Optional: Add a button to write a review -->
                        <button class="text-sm text-gray-600 hover:text-black">Write a Review</button>
                    </div>
                    <?php if (!empty($reviews)): ?>
                        <div class="space-y-6">
                            <?php foreach ($reviews as $review): ?>
                                <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="font-bold text-gray-900">
                                                <?= htmlspecialchars($review['reviewer_name']) ?>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <?= date('F j, Y', strtotime($review['created_at'])) ?>
                                            </p>
                                        </div>
                                        <div class="flex">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' ?> text-lg"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 leading-relaxed">
                                        <?= htmlspecialchars($review['comment']) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <p class="text-gray-500">No reviews yet</p>
                        </div>
                    <?php endif; ?>
                </section>
            </div>

            <!-- Location & Contact Sidebar -->
            <div class="lg:col-span-2">
                <div class="sticky top-24">
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                        <h3 class="font-bold text-lg mb-4">Location</h3>
                        <p class="text-gray-600 mb-4">
                            <?= htmlspecialchars($hotel['address']) ?>
                        </p>
                        <!-- Optional: Add a map here -->
                    </div>

                    <?php if ($hotel['owner_phone'] || $hotel['owner_email']): ?>
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="font-bold text-lg mb-4">Contact Information</h3>
                            <?php if ($hotel['owner_phone']): ?>
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-phone mr-3 text-gray-500"></i>
                                    <p class="text-gray-600"><?= htmlspecialchars($hotel['owner_phone']) ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($hotel['owner_email']): ?>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-3 text-gray-500"></i>
                                    <p class="text-gray-600"><?= htmlspecialchars($hotel['owner_email']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <script src="../../assets/js/view_hotel.js"></script>
</body>

</html>