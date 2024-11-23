<?php
session_start();
require_once '../db/config.php';
require_once '../middleware/checkUserAccess.php';

header('Content-Type: application/json');
checkUserAccess('owner');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false];

    try {
        $roomId = filter_var($_POST['roomId'], FILTER_VALIDATE_INT);
        $ownerId = $_SESSION['userId'];

        if (!$roomId) {
            throw new Exception('Invalid room ID');
        }

        // Verify that the room belongs to the owner's hotel
        $stmt = $conn->prepare("
            SELECT r.room_id 
            FROM hb_rooms r
            JOIN hb_hotels h ON r.hotel_id = h.hotel_id
            WHERE r.room_id = ? AND h.owner_id = ?
        ");
        $stmt->bind_param("ii", $roomId, $ownerId);
        $stmt->execute();

        if ($stmt->get_result()->num_rows === 0) {
            throw new Exception('Unauthorized access');
        }

        // Start transaction
        $conn->begin_transaction();

        try {
            // Delete room (cascading will handle bookings)
            $stmt = $conn->prepare("DELETE FROM hb_rooms WHERE room_id = ?");
            $stmt->bind_param("i", $roomId);

            if (!$stmt->execute()) {
                throw new Exception('Failed to delete room');
            }

            $conn->commit();
            $response['success'] = true;
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
