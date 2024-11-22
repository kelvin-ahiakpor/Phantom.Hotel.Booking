<?php
require '../functions/session_check.php';
require '../functions/booking_functions.php';

header("Content-Type: application/json");

// Ensure the user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$userId = $_SESSION['userId'];
$status = isset($_GET['status']) ? $_GET['status'] : null;

try {
    // Fetch bookings by status
    $bookings = getBookingsByStatus($userId, $status);
    echo json_encode(["success" => true, "bookings" => $bookings]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error fetching bookings: " . $e->getMessage()]);
}
?>
