<?php
require "../functions/session_check.php";
require "../db/config.php";

header("Content-Type: application/json");

// Validate POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['booking_id'], $input['check_in_date'], $input['check_out_date'], $input['guests'])) {
    echo json_encode(["success" => false, "message" => "Invalid input. All fields are required."]);
    exit;
}

$bookingId = (int) $input['booking_id'];
$checkInDate = $input['check_in_date'];
$checkOutDate = $input['check_out_date'];
$guests = (int) $input['guests'];

// Validate check-in and check-out dates
if (new DateTime($checkOutDate) <= new DateTime($checkInDate)) {
    echo json_encode(["success" => false, "message" => "Check-out date must be after check-in date."]);
    exit;
}

// Validate booking and room details
$stmt = $conn->prepare("SELECT b.room_id, r.capacity 
                        FROM hb_bookings b 
                        JOIN hb_rooms r ON b.room_id = r.room_id 
                        WHERE b.booking_id = ? AND b.user_id = ?");
$stmt->bind_param("ii", $bookingId, $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Booking not found or unauthorized."]);
    exit;
}

$booking = $result->fetch_assoc();
$roomCapacity = (int) $booking['capacity'];

// Validate guest count
if ($guests > $roomCapacity) {
    echo json_encode(["success" => false, "message" => "The selected room cannot accommodate the number of guests."]);
    exit;
}

// Calculate the number of nights
$nights = (new DateTime($checkOutDate))->diff(new DateTime($checkInDate))->days;

// Validate that the booking is at least 2 nights
if ($nights < 2) {
    echo json_encode(["success" => false, "message" => "The booking must be at least 2 nights."]);
    exit;
}

// Calculate new total price
$nights = (new DateTime($checkOutDate))->diff(new DateTime($checkInDate))->days;

// Update the booking
$updateStmt = $conn->prepare("UPDATE hb_bookings 
                              SET check_in_date = ?, check_out_date = ?, guests = ? 
                              WHERE booking_id = ?");
$updateStmt->bind_param("ssii", $checkInDate, $checkOutDate, $guests, $bookingId);

if ($updateStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Booking modified successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to modify booking."]);
}

// Close statements
$stmt->close();
$updateStmt->close();
?>