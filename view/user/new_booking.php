<?php
// Fetch hotels dynamically from the database
require '../../db/config.php';

$query = "
    SELECT 
        h.hotel_id, 
        h.hotel_name, 
        h.location, 
        h.description,
        MIN(r.price_per_night) AS min_price, 
        MAX(r.price_per_night) AS max_price,
        (
            SELECT hi.image_url 
            FROM hb_hotel_images hi 
            WHERE hi.hotel_id = h.hotel_id 
            LIMIT 1
        ) AS image_url
    FROM hb_hotels h
    JOIN hb_rooms r ON h.hotel_id = r.hotel_id
    WHERE h.availability = TRUE
    GROUP BY h.hotel_id, h.hotel_name, h.location, h.description
";

$result = $conn->query($query);

$hotels = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link href="../../assets/css/new-booking.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50 py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <h1 class="text-3xl font-serif font-bold text-gray-800">Phantom</h1>
            <button onclick="window.history.back()" class="text-black hover:text-gray-800">
                    Back
                </button>
        </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-3xl font-serif font-bold text-gray-900">Select a Hotel</h2>
            <div class="relative">
                <input
                    type="text"
                    id="hotelSearch"
                    placeholder="Search hotels..."
                    class="p-2 border border-gray-300 rounded-lg w-80" />
                <div id="dropdownResults" class="absolute bg-white border border-gray-300 rounded-lg mt-1 hidden max-h-60 overflow-y-auto w-80">
                    <!-- Dropdown results will go here -->
                </div>
            </div>
        </div>

        <div id="hotelGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($hotels as $hotel): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="<?= htmlspecialchars(!empty($hotel['image_url']) ? '../../' . $hotel['image_url'] : '../../assets/images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($hotel['hotel_name']) ?>" class="h-48 w-full object-cover">
                    <div class="p-6">
                        <h3 class="text-lg font-bold"><?= htmlspecialchars($hotel['hotel_name']) ?></h3>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($hotel['location']) ?></p>
                        <p class="text-sm text-gray-800 mt-2"><?= htmlspecialchars($hotel['description']) ?></p>
                        <p class="text-xl font-bold mt-4">
                            $<?= number_format($hotel['min_price'], 2) ?> - $<?= number_format($hotel['max_price'], 2) ?>/night
                        </p>
                        <button
                            class="mt-4 px-4 py-2 bg-black text-white hover:bg-zinc-600 transition duration-150"
                            onclick="window.location.href='booking_form.php?hotel_id=<?= $hotel['hotel_id'] ?>';">
                            Book Now
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="../../assets/js/search_hotels.js"></script>
</body>

</html>