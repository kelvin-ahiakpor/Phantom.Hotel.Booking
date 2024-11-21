<?php
require "../session_check.php";
require "../../db/config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../view/user/bookings.php");
    exit;
}

$userId = $_SESSION['userId'];
$hotelId = (int) $_POST['hotel_id'];
$checkIn = $_POST['check_in'];
$checkOut = $_POST['check_out'];
$guests = (int) $_POST['guests'];

// Fetch price_per_night for the selected hotel
$stmt = $conn->prepare("SELECT price_per_night FROM hb_hotels WHERE hotel_id = ? AND availability = TRUE");
$stmt->bind_param("i", $hotelId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Invalid or unavailable hotel.";
    exit;
}

$hotel = $result->fetch_assoc();
$stmt->close();

$pricePerNight = (float) $hotel['price_per_night'];

// Calculate total price
$nights = (new DateTime($checkOut))->diff(new DateTime($checkIn))->days;
$totalPrice = $nights * $pricePerNight;

try {
    // Insert booking into the database
    $stmt = $conn->prepare("INSERT INTO hb_bookings (user_id, hotel_id, check_in_date, check_out_date, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissd", $userId, $hotelId, $checkIn, $checkOut, $totalPrice);

    if ($stmt->execute()) {
        header("Location: ../../view/user/bookings.php?success=1");
        exit;
    } else {
        echo "Error saving booking.";
        exit;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
