<?php
session_start();
require_once '../db/config.php';
require_once '../middleware/checkUserAccess.php';

header('Content-Type: application/json');
checkUserAccess('owner');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'errors' => []];

    try {
        // Validate and sanitize input
        $hotelId = filter_var($_POST['hotelId'], FILTER_VALIDATE_INT);
        $roomType = htmlspecialchars(trim($_POST['roomType']));
        $capacity = filter_var($_POST['capacity'], FILTER_VALIDATE_INT);
        $pricePerNight = filter_var($_POST['pricePerNight'], FILTER_VALIDATE_FLOAT);
        $availability = isset($_POST['availability']) && $_POST['availability'] === '1';
        $ownerId = $_SESSION['userId'];

        // Validate owner owns this hotel
        $stmt = $conn->prepare("SELECT hotel_id FROM hb_hotels WHERE hotel_id = ? AND owner_id = ?");
        $stmt->bind_param("ii", $hotelId, $ownerId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            throw new Exception("Unauthorized access");
        }

        // Validation
        if (empty($roomType)) {
            $response['errors'][] = 'Room type is required';
        }
        if (!$capacity || $capacity < 1) {
            $response['errors'][] = 'Capacity must be at least 1';
        }
        if (!$pricePerNight || $pricePerNight <= 0) {
            $response['errors'][] = 'Invalid price';
        }

        // If no errors, create the room
        if (empty($response['errors'])) {
            $stmt = $conn->prepare("
                INSERT INTO hb_rooms (
                    hotel_id,
                    room_type,
                    capacity,
                    price_per_night,
                    availability
                ) VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "isidi",
                $hotelId,
                $roomType,
                $capacity,
                $pricePerNight,
                $availability
            );

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['roomId'] = $conn->insert_id;
            } else {
                throw new Exception("Failed to create room");
            }
        }
    } catch (Exception $e) {
        $response['errors'][] = $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
