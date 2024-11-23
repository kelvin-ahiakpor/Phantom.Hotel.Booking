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
        $roomId = filter_var($_POST['roomId'], FILTER_VALIDATE_INT);
        $hotelId = filter_var($_POST['hotelId'], FILTER_VALIDATE_INT);
        $roomType = htmlspecialchars(trim($_POST['roomType']));
        $capacity = filter_var($_POST['capacity'], FILTER_VALIDATE_INT);
        $pricePerNight = filter_var($_POST['pricePerNight'], FILTER_VALIDATE_FLOAT);
        $availability = isset($_POST['availability']) && $_POST['availability'] === '1';
        $ownerId = $_SESSION['userId'];

        // Basic validation
        if (empty($roomType)) {
            $response['errors'][] = 'Room type is required';
        }
        if (!$capacity || $capacity < 1) {
            $response['errors'][] = 'Capacity must be at least 1';
        }
        if (!$pricePerNight || $pricePerNight <= 0) {
            $response['errors'][] = 'Invalid price';
        }
        if (!$roomId) {
            $response['errors'][] = 'Invalid room ID';
        }

        // If no validation errors, proceed with verification and update
        if (empty($response['errors'])) {
            // First verify that the room belongs to the owner's hotel
            $stmt = $conn->prepare("
                SELECT r.room_id 
                FROM hb_rooms r
                JOIN hb_hotels h ON r.hotel_id = h.hotel_id
                WHERE r.room_id = ? 
                AND h.owner_id = ?
            ");
            $stmt->bind_param("ii", $roomId, $ownerId);
            $stmt->execute();

            if ($stmt->get_result()->num_rows === 0) {
                throw new Exception('Unauthorized access');
            }

            // Check if the room has any active bookings before updating availability
            if (!$availability) {
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as active_bookings
                    FROM hb_bookings
                    WHERE room_id = ? 
                    AND check_out_date >= CURDATE()
                ");
                $stmt->bind_param("i", $roomId);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();

                if ($result['active_bookings'] > 0) {
                    throw new Exception('Cannot mark room as unavailable while there are active bookings');
                }
            }

            // Start transaction
            $conn->begin_transaction();

            try {
                // Update room details
                $stmt = $conn->prepare("
                    UPDATE hb_rooms 
                    SET room_type = ?,
                        capacity = ?,
                        price_per_night = ?,
                        availability = ?
                    WHERE room_id = ?
                    AND hotel_id = ?
                ");

                $stmt->bind_param(
                    "sidiii",
                    $roomType,
                    $capacity,
                    $pricePerNight,
                    $availability,
                    $roomId,
                    $hotelId
                );

                if ($stmt->execute()) {
                    // Check if any rows were actually updated
                    if ($stmt->affected_rows > 0) {
                        $conn->commit();
                        $response['success'] = true;
                        $response['message'] = 'Room updated successfully';
                    } else {
                        throw new Exception('No changes were made to the room');
                    }
                } else {
                    throw new Exception('Failed to update room');
                }
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
        }
    } catch (Exception $e) {
        $response['errors'][] = $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
