<?php
require "../../functions/session_check.php";
require "../../db/config.php";
require_once '../../middleware/checkUserAccess.php';
checkUserAccess('user');

if (!isset($_GET['hotel_id'])) {
    die("Invalid request. Hotel ID is required.");
}

$hotel_id = intval($_GET['hotel_id']);

// Fetch hotel details from the database
$query = "SELECT hotel_name, location, description FROM hb_hotels WHERE hotel_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Hotel not found.");
}

$hotel = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?= htmlspecialchars($hotel['hotel_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
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
        <h2 class="text-3xl font-serif font-bold text-gray-900 mb-6">Book <?= htmlspecialchars($hotel['hotel_name']) ?></h2>
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h3 class="text-xl font-semibold mb-4"><?= htmlspecialchars($hotel['hotel_name']) ?></h3>
            <p class="text-gray-700 mb-2"><strong>Location:</strong> <?= htmlspecialchars($hotel['location']) ?></p>
            <p class="text-gray-700 mb-4"><strong>Description:</strong> <?= htmlspecialchars($hotel['description']) ?></p>


            <form novalidate class="space-y-4">
                <input type="hidden" name="hotel_id" value="<?= $hotel_id ?>">

                <div>
                    <label for="check_in_date" class="block text-gray-700">Check-in Date</label>
                    <input type="date" id="check_in_date" name="check_in_date" required class="mt-1 p-2 border border-gray-300 w-full">
                </div>

                <div>
                    <label for="check_out_date" class="block text-gray-700">Check-out Date</label>
                    <input type="date" id="check_out_date" name="check_out_date" required class="mt-1 p-2 border border-gray-300 w-full">
                </div>


                <div>
                    <label for="room_id" class="block text-gray-700">Select Room</label>
                    <select id="room_id" name="room_id" required class="mt-1 p-2 border border-gray-300 w-full">
                        <option value="">Loading rooms...</option>
                    </select>
                </div>

                <div>
                    <label for="guests" class="block text-gray-700">Number of Guests</label>
                    <input type="number" id="guests" name="guests" min="1" required class="mt-1 p-2 border border-gray-300 w-full">
                </div>

                <button id="submitBooking" type="submit" class="w-full px-4 py-2 bg-black text-white hover:bg-zinc-600 transition duration-150">
                    Confirm Booking
                </button>
            </form>
            <div id="formFeedback" class="mt-4"></div>
        </div>
    </main>
    <script type="module" src="../../assets/js/booking_form.js"></script>
</body>

</html>