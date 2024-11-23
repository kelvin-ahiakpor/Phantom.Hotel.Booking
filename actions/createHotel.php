<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'errors' => []];

    try {
        // Validate and sanitize input
        $hotelName = htmlspecialchars(trim($_POST['hotelName']));
        $location = htmlspecialchars(trim($_POST['hotelLocation']));
        $description = htmlspecialchars(trim($_POST['hotelDescription']));
        $ownerId = $_SESSION['userId'];

        // Basic validation
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
                    $imageUrls[] = $imagePath;
                } else {
                    $response['errors'][] = "Failed to upload {$field}";
                }
            }
        }

        // If there are no errors, proceed with hotel creation
        if (empty($response['errors'])) {
            // Start transaction
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

                // Set default price
                $address = $location;

                $stmt->bind_param(
                    "sssss",
                    $hotelName,
                    $location,
                    $address,
                    $description,
                    $ownerId
                );

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

                    // Process amenities
                    $amenities = [
                        'wifi' => isset($_POST['wifi']),
                        'pool' => isset($_POST['pool']),
                        'spa' => isset($_POST['spa']),
                        'restaurant' => isset($_POST['restaurant']),
                        'valet' => isset($_POST['valet']),
                        'concierge' => isset($_POST['concierge'])
                    ];

                    $conn->commit();
                    $response['success'] = true;
                    $response['redirect'] = 'manage_hotel.php';
                } else {
                    throw new Exception("Error creating hotel");
                }
            } catch (Exception $e) {
                $conn->rollback();
                $response['errors'][] = 'Database error: ' . $e->getMessage();

                // Clean up uploaded images if hotel creation failed
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

    // Send JSON response
    echo json_encode($response);
    exit;
}
