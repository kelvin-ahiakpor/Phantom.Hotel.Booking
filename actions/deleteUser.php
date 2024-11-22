<?php
require '../functions/session_check.php';
require '../db/config.php';

// Ensure the user is logged in and is a Super Admin
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 1) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

// Ensure the request method is POST and `user_id` is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!$userId) {
        echo json_encode(["success" => false, "message" => "Invalid user ID."]);
        exit;
    }

    try {
        $stmt = $conn->prepare("DELETE FROM rs_users WHERE user_id = ?");
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
            exit;
        }

        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "message" => "User deleted successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "No user found with this ID."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Error executing query: " . $stmt->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Exception: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>