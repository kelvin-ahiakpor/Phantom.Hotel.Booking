<?php
require "../session_check.php";
require "../../db/config.php";

header("Content-Type: application/json");

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["success" => false, "message" => "Invalid request payload."]);
    exit;
}

$userId = $_SESSION['userId'];
$hotelId = (int) $input['hotel_id'];
$checkIn = $input['check_in'];
$checkOut = $input['check_out'];
$guests = (int) $input['guests'];

// Validate input data
if (!$hotelId || !$checkIn || !$checkOut || $guests <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid input data."]);
    exit;
}

// Ensure check-in is before check-out
if (new DateTime($checkOut) <= new DateTime($checkIn)) {
    echo json_encode(["success" => false, "message" => "Check-out must be after check-in."]);
    exit;
}

// Fetch price_per_night for the selected hotel
$stmt = $conn->prepare("SELECT price_per_night FROM hb_hotels WHERE hotel_id = ? AND availability = TRUE");
$stmt->bind_param("i", $hotelId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Hotel not available for booking."]);
    exit;
}

$hotel = $result->fetch_assoc();
$pricePerNight = (float) $hotel['price_per_night'];

// Calculate total price
$nights = (new DateTime($checkOut))->diff(new DateTime($checkIn))->days;
$totalPrice = $nights * $pricePerNight;

// Save booking to database
try {
    $stmt = $conn->prepare("INSERT INTO hb_bookings (user_id, hotel_id, check_in_date, check_out_date, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissd", $userId, $hotelId, $checkIn, $checkOut, $totalPrice);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Booking successful."]);
        exit;
    } else {
        echo json_encode(["success" => false, "message" => "Failed to save booking."]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Server error: " . $e->getMessage()]);
    exit;
}
?>
