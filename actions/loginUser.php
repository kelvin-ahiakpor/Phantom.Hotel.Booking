<?php
session_start();
require '../db/config.php';

$errors = [];

// Decode the JSON input
$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $email = filter_var(trim($input["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($input["password"]);

    if (empty($email)) {
        $errors[] = ["field" => "email", "message" => "Email is required."];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = ["field" => "email", "message" => "Invalid email format."];
    }

    if (empty($password)) {
        $errors[] = ["field" => "password", "message" => "Password is required."];
    }

    if (empty($errors)) {
        // Query database for user
        $stmt = $conn->prepare("SELECT user_id, password, first_name, last_name, user_type FROM hb_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId, $hashedPassword, $firstName, $lastName, $userType);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashedPassword)) {
                // Set session variables
                $_SESSION['userId'] = $userId;
                $_SESSION['email'] = $email;
                $_SESSION['firstName'] = $firstName;
                $_SESSION['lastName'] = $lastName;
                $_SESSION['userType'] = $userType;

                // Redirect to dashboard or hotel feed
                echo json_encode(["success" => true, "redirect" => "../view/hotel_feed.php"]);
                exit;
            } else {
                $errors[] = ["field" => "password", "message" => "Incorrect password."];
            }
        } else {
            $errors[] = ["field" => "email", "message" => "No account found with this email."];
        }

        $stmt->close();
    }

    // Return errors
    echo json_encode(["success" => false, "errors" => $errors]);
    exit;
}
?>