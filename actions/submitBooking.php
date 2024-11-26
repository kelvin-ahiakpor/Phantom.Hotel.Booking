<?php
require "../functions/session_check.php";
require "../db/config.php";

header("Content-Type: application/json");

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// Parse JSON input
$input = json_decode(file_get_contents("php://input"), true);
$today = new DateTime();

if (!$input) {
    echo json_encode(["success" => false, "message" => "Invalid request payload."]);
    exit;
}

$userId = $_SESSION['userId'];
$hotelId = (int) $input['hotel_id'];
$roomId = (int) $input['room_id'];
$checkIn = $input['check_in'];
$checkOut = $input['check_out'];
$guests = (int) $input['guests'];

// Validate input data
if (!$hotelId || !$roomId || !$checkIn || !$checkOut || $guests <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid input data."]);
    exit;
}

if (new DateTime($checkOut) <= new DateTime($checkIn)) {
    echo json_encode(["success" => false, "message" => "Check-out date must be after check-in date."]);
    exit;
}

if (new DateTime($checkIn) < $today) {
    echo json_encode(["success" => false, "message" => "Check-in date cannot be in the past."]);
    exit;
}

if (new DateTime($checkOut) <= new DateTime($checkIn)) {
    echo json_encode(["success" => false, "message" => "Check-out date must be after the check-in date."]);
    exit;
}

// Calculate the number of nights
$nights = (new DateTime($checkOut))->diff(new DateTime($checkIn))->days;

// Check if the booking is at least 2 nights
if ($nights < 2) {
    echo json_encode(["success" => false, "message" => "The booking must be at least 2 nights."]);
    exit;
}

// Validate room availability and capacity
$stmt = $conn->prepare("SELECT room_id, price_per_night, capacity FROM hb_rooms WHERE hotel_id = ? AND room_id = ? AND availability = TRUE");
$stmt->bind_param("ii", $hotelId, $roomId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "The selected room is not available."]);
    exit;
}

$room = $result->fetch_assoc();
$pricePerNight = (float) $room['price_per_night'];
$roomCapacity = (int) $room['capacity'];

// Check if the number of guests exceeds the room's capacity
if ($guests > $roomCapacity) {
    echo json_encode(["success" => false, "message" => "The selected room cannot accommodate the number of guests."]);
    exit;
}

// Calculate total price
$nights = (new DateTime($checkOut))->diff(new DateTime($checkIn))->days;
if ($nights <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid duration. Check-out date must be at least one day after the check-in date."]);
    exit;
}
$totalPrice = $nights * $pricePerNight;

// Save booking to database
try {
    $stmt = $conn->prepare("INSERT INTO hb_bookings (user_id, hotel_id, room_id, check_in_date, check_out_date, guests, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissid", $userId, $hotelId, $roomId, $checkIn, $checkOut, $guests, $totalPrice);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Booking successful.", "booking_id" => $stmt->insert_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to save booking."]);
    }
} catch (Exception $e) {
    error_log("Booking error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Server error: " . $e->getMessage()]);
} finally {
    $stmt->close();
}
?>