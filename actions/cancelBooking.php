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

if (!isset($input['booking_id'])) {
    echo json_encode(["success" => false, "message" => "Booking ID is required."]);
    exit;
}

$bookingId = (int) $input['booking_id'];

// Check if booking exists and belongs to the user
$stmt = $conn->prepare("SELECT booking_id FROM hb_bookings WHERE booking_id = ? AND user_id = ?");
$stmt->bind_param("ii", $bookingId, $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Booking not found or unauthorized."]);
    exit;
}

// Cancel the booking
$cancelStmt = $conn->prepare("UPDATE hb_bookings SET status = 'cancelled' WHERE booking_id = ?");
$cancelStmt->bind_param("i", $bookingId);

if ($cancelStmt->execute() && $cancelStmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Booking cancelled successfully."]);
} else {
    error_log("Error cancelling booking: " . $cancelStmt->error