<?php
require '../functions/session_check.php';
require "../db/config.php";

header("Content-Type: application/json");

if (!isset($_GET['hotel_id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request. Hotel ID is required."]);
    exit;
}

$hotel_id = intval($_GET['hotel_id']);

// Fetch available rooms for the hotel
$query = "SELECT room_id, room_type, price_per_night, capacity FROM hb_rooms WHERE hotel_id = ? AND availability = TRUE";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $rooms = [];
    while ($room = $result->fetch_assoc()) {
        $rooms[] = $room;
    }
    echo json_encode(["success" => true, "rooms" => $rooms]);
} else {
    echo json_encode(["success" => false, "message" => "No rooms available for booking."]);
}
$stmt->close();
$conn->close();
?>
