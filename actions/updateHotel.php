<?php
session_start();
error_reporting(0); // Disable error reporting to prevent HTML in output
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once '../db/config.php';
require_once '../middleware/checkUserAccess.php';

// Ensure all database operations fail gracefully
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Function to send JSON response
function sendJsonResponse($success, $errors = []) {
    echo json_encode([
        'success' => $success,
        'errors' => (array)$errors
    ]);
    exit;
}

// Handle file upload
function uploadImage($file) {
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

        $stmt->execute();

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
        sendJsonResponse(true);

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
        sendJsonResponse(false, "Database error occurred");
    }

} catch (Exception $e) {
    error_log("Server error: " . $e->getMessage());
    sendJsonResponse(false, "Server error occurred");
}