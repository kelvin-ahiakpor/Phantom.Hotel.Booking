<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once '../db/config.php';
require_once '../middleware/checkUserAccess.php';

// Ensure all database operations fail gracefully
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Function to send JSON response
function sendJsonResponse($success, $errors = [], $data = [])
{
    echo json_encode([
        'success' => $success,
        'errors' => (array)$errors,
        'data' => $data
    ]);
    exit;
}

// Handle file upload
function uploadImage($file)
{
    try {
        $uploadDir = '../../uploads/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception("Failed to create upload directory");
            }
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Invalid file type");
        }

        // Validate file size (5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception("File size exceeds limit");
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("Failed to move uploaded file");
        }

        return 'uploads/' . $filename;
    } catch (Exception $e) {
        error_log("Image upload error: " . $e->getMessage());
        return null;
    }
}

try {
    // Check user access
    if (!isset($_SESSION['userId'])) {
        sendJsonResponse(false, "Unauthorized access");
    }

    checkUserAccess('owner');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse(false, "Invalid request method");
    }

    // Validate and sanitize input
    $hotelId = filter_var($_POST['hotelId'] ?? null, FILTER_VALIDATE_INT);
    $hotelName = trim($_POST['hotelName'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $availability = isset($_POST['availability']) && $_POST['availability'] === '1';
    $ownerId = $_SESSION['userId'];

    // Get amenities values
    $amenities = [
        'wifi' => isset($_POST['wifi']) && $_POST['wifi'] === '1',
        'pool' => isset($_POST['pool']) && $_POST['pool'] === '1',
        'spa' => isset($_POST['spa']) && $_POST['spa'] === '1',
        'restaurant' => isset($_POST['restaurant']) && $_POST['restaurant'] === '1',
        'valet' => isset($_POST['valet']) && $_POST['valet'] === '1',
        'concierge' => isset($_POST['concierge']) && $_POST['concierge'] === '1'
    ];

    // Input validation
    $errors = [];
    if (!$hotelId) $errors[] = 'Invalid hotel ID';
    if (empty($hotelName)) $errors[] = 'Hotel name is required';
    if (empty($location)) $errors[] = 'Location is required';
    if (empty($address)) $errors[] = 'Address is required';
    if (empty($description)) $errors[] = 'Description is required';

    if (!empty($errors)) {
        sendJsonResponse(false, $errors);
    }

    // Verify ownership
    $stmt = $conn->prepare("SELECT hotel_id FROM hb_hotels WHERE hotel_id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $hotelId, $ownerId);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        sendJsonResponse(false, "Unauthorized access");
    }

    // Process images
    $newImageUrls = [];
    $imageFields = ['hotelImage1', 'hotelImage2', 'hotelImage3'];

    foreach ($imageFields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $imagePath = uploadImage($_FILES[$field]);
            if ($imagePath) {
                $newImageUrls[] = $imagePath;
            } else {
                $errors[] = "Failed to upload {$field}";
            }
        }
    }

    if (!empty($errors)) {
        sendJsonResponse(false, $errors);
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update hotel details
        $stmt = $conn->prepare("
            UPDATE hb_hotels 
            SET hotel_name = ?, 
                location = ?, 
                address = ?,
                description = ?, 
                availability = ?
            WHERE hotel_id = ? AND owner_id = ?
        ");

        $stmt->bind_param(
            "ssssiii",
            $hotelName,
            $location,
            $address,
            $description,
            $availability,
            $hotelId,
            $ownerId
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to update hotel details");
        }

        // Update amenities
        // First, check if amenities record exists
        $amenityCheckStmt = $conn->prepare("
            SELECT amenity_id FROM hb_hotel_amenities WHERE hotel_id = ?
        ");
        $amenityCheckStmt->bind_param("i", $hotelId);
        $amenityCheckStmt->execute();
        $amenityExists = $amenityCheckStmt->get_result()->num_rows > 0;

        if ($amenityExists) {
            // Update existing amenities
            $amenityStmt = $conn->prepare("
                UPDATE hb_hotel_amenities 
                SET wifi = ?,
                    pool = ?,
                    spa = ?,
                    restaurant = ?,
                    valet = ?,
                    concierge = ?
                WHERE hotel_id = ?
            ");
        } else {
            // Insert new amenities
            $amenityStmt = $conn->prepare("
                INSERT INTO hb_hotel_amenities (
                    hotel_id, wifi, pool, spa, restaurant, valet, concierge
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
        }

        $wifiVal = $amenities['wifi'] ? 1 : 0;
        $poolVal = $amenities['pool'] ? 1 : 0;
        $spaVal = $amenities['spa'] ? 1 : 0;
        $restaurantVal = $amenities['restaurant'] ? 1 : 0;
        $valetVal = $amenities['valet'] ? 1 : 0;
        $conciergeVal = $amenities['concierge'] ? 1 : 0;

        $amenityStmt->bind_param(
            "iiiiiii",
            $hotelId,
            $wifiVal,
            $poolVal,
            $spaVal,
            $restaurantVal,
            $valetVal,
            $conciergeVal
        );

        if (!$amenityStmt->execute()) {
            throw new Exception("Failed to update amenities");
        }

        // Handle image updates if new images were uploaded
        if (!empty($newImageUrls)) {
            // Get existing images
            $stmt = $conn->prepare("SELECT image_url FROM hb_hotel_images WHERE hotel_id = ?");
            $stmt->bind_param("i", $hotelId);
            $stmt->execute();
            $result = $stmt->get_result();
            $oldImages = [];
            while ($row = $result->fetch_assoc()) {
                $oldImages[] = $row['image_url'];
            }

            // Delete old image records
            $stmt = $conn->prepare("DELETE FROM hb_hotel_images WHERE hotel_id = ?");
            $stmt->bind_param("i", $hotelId);
            $stmt->execute();

            // Insert new images
            $stmt = $conn->prepare("INSERT INTO hb_hotel_images (hotel_id, image_url) VALUES (?, ?)");
            foreach ($newImageUrls as $url) {
                $stmt->bind_param("is", $hotelId, $url);
                $stmt->execute();
            }

            // Clean up old image files
            foreach ($oldImages as $oldImage) {
                $fullPath = '../../' . $oldImage;
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        }

        $conn->commit();
        sendJsonResponse(true, [], [
            'message' => 'Hotel updated successfully',
            'hotelId' => $hotelId
        ]);
    } catch (Exception $e) {
        $conn->rollback();

        // Clean up newly uploaded images if update failed
        foreach ($newImageUrls as $imagePath) {
            $fullPath = '../../' . $imagePath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        error_log("Database error: " . $e->getMessage());
        sendJsonResponse(false, "Failed to update hotel: " . $e->getMessage());
    }
} catch (Exception $e) {
    error_log("Server error: " . $e->getMessage());
    sendJsonResponse(false, "Server error occurred");
}
