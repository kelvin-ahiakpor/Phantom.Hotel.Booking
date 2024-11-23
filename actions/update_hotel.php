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
        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/' . $filename;
        }
        return null;
    } catch (Exception $e) {
        error_log("Image upload error: " . $e->getMessage());
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'errors' => []];

    try {
        // Validate and sanitize input
        $hotelId = filter_var($_POST['hotelId'], FILTER_VALIDATE_INT);
        $hotelName = htmlspecialchars(trim($_POST['hotelName']));
        $location = htmlspecialchars(trim($_POST['location']));
        $address = htmlspecialchars(trim($_POST['address']));
        $description = htmlspecialchars(trim($_POST['description']));
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

        // Basic validation
        if (empty($hotelName)) $response['errors'][] = 'Hotel name is required';
        if (empty($location)) $response['errors'][] = 'Location is required';
        if (empty($address)) $response['errors'][] = 'Address is required';
        if (empty($description)) $response['errors'][] = 'Description is required';
        if ($pricePerNight <= 0) $response['errors'][] = 'Invalid price';

        // Process images
        $newImageUrls = [];
        $imageFields = ['hotelImage1', 'hotelImage2', 'hotelImage3'];

        foreach ($imageFields as $field) {
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($_FILES[$field]['type'], $allowedTypes)) {
                    $response['errors'][] = "Invalid file type for {$field}. Only JPG and PNG are allowed.";
                    continue;
                }

                // Validate file size (5MB max)
                if ($_FILES[$field]['size'] > 5 * 1024 * 1024) {
                    $response['errors'][] = "File size too large for {$field}. Maximum size is 5MB.";
                    continue;
                }

                $imagePath = uploadImage($_FILES[$field]);
                if ($imagePath) {
                    $newImageUrls[] = $imagePath;
                } else {
                    $response['errors'][] = "Failed to upload {$field}";
                }
            }
        }

        // If there are no errors, proceed with hotel update
        if (empty($response['errors'])) {
            $conn->begin_transaction();

            try {
                // Update hotel record
                $stmt = $conn->prepare("
                    UPDATE hb_hotels 
                    SET hotel_name = ?, 
                        location = ?, 
                        address = ?,
                        description = ?, 
                        price_per_night = ?,
                        availability = ?
                    WHERE hotel_id = ? AND owner_id = ?
                ");

                $stmt->bind_param(
                    "ssssdiii",
                    $hotelName,
                    $location,
                    $address,
                    $description,
                    $pricePerNight,
                    $availability,
                    $hotelId,
                    $ownerId
                );

                if ($stmt->execute()) {
                    // Handle new images if uploaded
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

                        // Delete old images
                        $stmt = $conn->prepare("DELETE FROM hb_hotel_images WHERE hotel_id = ?");
                        $stmt->bind_param("i", $hotelId);
                        $stmt->execute();

                        // Insert new images
                        $stmt = $conn->prepare("INSERT INTO hb_hotel_images (hotel_id, image_url) VALUES (?, ?)");
                        foreach ($newImageUrls as $url) {
                            $stmt->bind_param("is", $hotelId, $url);
                            $stmt->execute();
                        }

                        // Delete old image files
                        foreach ($oldImages as $oldImage) {
                            $fullPath = '../../' . $oldImage;
                            if (file_exists($fullPath)) {
                                unlink($fullPath);
                            }
                        }
                    }

                    $conn->commit();
                    $response['success'] = true;
                } else {
                    throw new Exception("Error updating hotel");
                }
            } catch (Exception $e) {
                $conn->rollback();
                $response['errors'][] = 'Database error: ' . $e->getMessage();

                // Clean up newly uploaded images if update failed
                foreach ($newImageUrls as $imagePath) {
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
