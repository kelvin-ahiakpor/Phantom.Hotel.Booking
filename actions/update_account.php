<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once '../db/config.php';
require_once '../middleware/checkUserAccess.php';

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

// Function to validate email format
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate phone number format
function isValidPhone($phone)
{
    return empty($phone) || preg_match('/^\+?[\d\s-]{10,}$/', $phone);
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['userId'])) {
        sendJsonResponse(false, ['Unauthorized access']);
    }

    $userId = $_SESSION['userId'];

    // Validate and sanitize input
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Input validation
    $errors = [];

    // Required fields
    if (empty($firstName)) $errors[] = 'First name is required';
    if (empty($lastName)) $errors[] = 'Last name is required';
    if (empty($email)) $errors[] = 'Email is required';

    // Email format
    if (!isValidEmail($email)) {
        $errors[] = 'Invalid email format';
    }

    // Phone number format (if provided)
    if (!isValidPhone($phoneNumber)) {
        $errors[] = 'Invalid phone number format';
    }

    // Check if email is already taken by another user
    $stmt = $conn->prepare("
        SELECT user_id 
        FROM hb_users 
        WHERE email = ? AND user_id != ?
    ");
    $stmt->bind_param("si", $email, $userId);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = 'Email is already in use';
    }

    // Password validation (if changing password)
    $updatePassword = false;
    if ($currentPassword || $newPassword || $confirmPassword) {
        if (empty($currentPassword)) {
            $errors[] = 'Current password is required';
        }
        if (empty($newPassword)) {
            $errors[] = 'New password is required';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'New passwords do not match';
        }
        if (strlen($newPassword) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }

        // Verify current password
        if ($currentPassword) {
            $stmt = $conn->prepare("SELECT password FROM hb_users WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!password_verify($currentPassword, $user['password'])) {
                $errors[] = 'Current password is incorrect';
            } else {
                $updatePassword = true;
            }
        }
    }

    if (!empty($errors)) {
        sendJsonResponse(false, $errors);
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update user details
        $stmt = $conn->prepare("
            UPDATE hb_users 
            SET first_name = ?,
                last_name = ?,
                email = ?,
                phone_number = ?
            WHERE user_id = ?
        ");

        $stmt->bind_param(
            "ssssi",
            $firstName,
            $lastName,
            $email,
            $phoneNumber,
            $userId
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to update user details");
        }

        // Update password if requested
        if ($updatePassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                UPDATE hb_users 
                SET password = ?
                WHERE user_id = ?
            ");

            $stmt->bind_param("si", $hashedPassword, $userId);

            if (!$stmt->execute()) {
                throw new Exception("Failed to update password");
            }
        }

        // Update session data
        $_SESSION['firstName'] = $firstName;
        $_SESSION['email'] = $email;

        $conn->commit();

        sendJsonResponse(true, [], [
            'message' => 'Account updated successfully',
            'updatePassword' => $updatePassword
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Database error: " . $e->getMessage());
        sendJsonResponse(false, ['Failed to update account']);
    }
} catch (Exception $e) {
    error_log("Server error: " . $e->getMessage());
    sendJsonResponse(false, ['An unexpected error occurred']);
}

// Close database connection
if (isset($conn)) {
    $conn->close();
}
