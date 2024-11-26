<?php
session_start();
header('Content-Type: application/json');
require_once '../db/config.php';
require_once '../middleware/checkUserAccess.php';

try {
    // Check user access
    checkUserAccess('owner');

    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $hotelId = $data['hotelId'] ?? null;
    $ownerId = $_SESSION['userId'];

    if (!$hotelId) {
        throw new Exception('Hotel ID is required');
    }

    // Verify ownership
    $stmt = $conn->prepare("SELECT hotel_id FROM hb_hotels WHERE hotel_id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $hotelId, $ownerId);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        throw new Exception('Unauthorized access');
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete hotel images first
        $stmt = $conn->prepare("SELECT image_url FROM hb_hotel_images WHERE hotel_id = ?");
        $stmt->bind_param("i", $hotelId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $imagePath = '../../' . $row['image_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete related records
        $tables = ['hb_hotel_images', 'hb_hotel_amenities', 'hb_reviews', 'hb_rooms', 'hb_hotels'];

        foreach ($tables as $table) {
            $stmt = $conn->prepare("DELETE FROM $table WHERE hotel_id = ?");
            $stmt->bind_param("i", $hotelId);
            $stmt->execute();
        }

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
