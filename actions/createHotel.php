<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once '../db/config.php';
require_once '../middleware/checkUserAccess.php';

// Check if user is owner
checkUserAccess('owner');

function uploadImage($file)
{
    try {
        // Create upload directory if it doesn't exist
        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Return relative path for database storage
            return 'uploads/' . $filename;
        }
        return null;
    } catch (Exception $e) {
        error_log("Image upload error: " . $e->getMessage());
        return null;
    }
}

// Handle the hotel creation request
// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'errors' => []];

    try {
        $hotelName = htmlspecialchars(trim($_POST['hotelName']));
        $location = htmlspecialchars(trim($_POST['hotelLocation']));
        $description = htmlspecialchars(trim($_POST['hotelDescription']));
        $ownerId = $_SESSION['userId'];

        // Validate input
        if (empty($hotelName)) {
            $response['errors'][] = 'Hotel name is required';
        }
        if (empty($location)) {
            $response['errors'][] = 'Location is required';
        }
        if (empty($description)) {
            $response['errors'][] = 'Description is required';
        }

        // Process images
        $imageUrls = [];
        $imageFields = ['hotelImage1', 'hotelImage2', 'hotelImage3'];
        foreach ($imageFields as $field) {
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png'];
                if (!in_array($_FILES[$field]['type'], $allowedTypes)) {
                    $response['errors'][] = "Invalid file type for {$field}. Only JPG and PNG are allowed.";
                    continue;
                }

                if ($_FILES[$field]['size'] > 5 * 1024 * 1024) {
                    $response['errors'][] = "File size too large for {$field}. Maximum size is 5MB.";
                    continue;
                }

                $imagePath = uploadImage($_FILES[$field]);
                if ($imagePath) {
                    $imageUrls[] = $imagePath;
                } else {
                    $response['errors'][] = "Failed to upload {$field}";
                }
            }
        }

        // Dynamically handle amenities
        $amenities = ['wifi', 'pool', 'spa', 'restaurant', 'valet', 'concierge'];
        $amenityValues = [];
        foreach ($amenities as $amenity) {
            $amenityValues[$amenity] = isset($_POST[$amenity]) && $_POST[$amenity] === '1' ? 1 : 0;
        }

        if (empty($response['errors'])) {
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Insert hotel record
                $stmt = $conn->prepare("
                    INSERT INTO hb_hotels (
                        hotel_name,
                        location,
                        address,
                        description,
                        owner_id,
                        availability,
                        created_at
                    ) VALUES (?, ?, ?, ?, ?, TRUE, NOW())
                ");

                $address = $location; // Using location as address for now
                $stmt->bind_param("ssssi", $hotelName, $location, $address, $description, $ownerId);

                if ($stmt->execute()) {
                    $hotelId = $conn->insert_id;

                    // Store hotel images
                    if (!empty($imageUrls)) {
                        $imageStmt = $conn->prepare("
                            INSERT INTO hb_hotel_images (hotel_id, image_url) 
                            VALUES (?, ?)
                        ");
                        foreach ($imageUrls as $imagePath) {
                            $imageStmt->bind_param("is", $hotelId, $imagePath);
                            $imageStmt->execute();
                        }
                    }

                    // Insert amenities
                    $amenitiesStmt = $conn->prepare("
                        INSERT INTO hb_hotel_amenities (
                            hotel_id, wifi, pool, spa, restaurant, valet, concierge
                        ) VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    $amenitiesStmt->bind_param(
                        "iiiiiii",
                        $hotelId,
                        $amenityValues['wifi'],
                        $amenityValues['pool'],
                        $amenityValues['spa'],
                        $amenityValues['restaurant'],
                        $amenityValues['valet'],
                        $amenityValues['concierge']
                    );

                    if (!$amenitiesStmt->execute()) {
                        throw new Exception("Error saving amenities");
                    }

                    $conn->commit();
                    $response['success'] = true;
                    $response['redirect'] = 'manage_hotel.php';
                    $response['hotelId'] = $hotelId;
                } else {
                    throw new Exception("Error creating hotel");
                }
            } catch (Exception $e) {
                $conn->rollback();
                $response['errors'][] = 'Transaction failed: ' . $e->getMessage();

                // Clean up uploaded images
                foreach ($imageUrls as $imagePath) {
                    $fullPath = '../' . $imagePath;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
        }
    } catch (Exception $e) {
        $response['errors'][] = 'Server error: ' . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
