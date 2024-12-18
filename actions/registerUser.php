<?php
session_start();
require '../db/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = [];
$input = json_decode(file_get_contents("php://input"), true);

// Debug: Log input to error log
error_log(print_r($input, true));

if ($_SERVER["REQUEST_METHOD"] == "POST" && $input) {
    $firstName = htmlspecialchars(trim($input["first_name"]), ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars(trim($input["last_name"]), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($input["email"]), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($input["phone"] ?? ''), ENT_QUOTES, 'UTF-8');
    $password = trim($input["password"]);
    $passwordConfirm = trim($input["confirm_password"]);
    $userType = htmlspecialchars(trim($input["user_type"]), ENT_QUOTES, 'UTF-8');

    // Validation checks (existing checks remain the same)
    if (empty($firstName)) {
        $errors[] = ["field" => "first-name", "message" => "First name is required."];
    }

    if (empty($lastName)) {
        $errors[] = ["field" => "last-name", "message" => "Last name is required."];
    }

    if (empty($email)) {
        $errors[] = ["field" => "email", "message" => "Email is required."];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = ["field" => "email", "message" => "Invalid email format."];
    }

    // Phone validation (optional but must be valid if provided)
    if (!empty($phone) && !preg_match('/^\+?[\d\s-]{10,}$/', $phone)) {
        $errors[] = ["field" => "phone", "message" => "Please enter a valid phone number."];
    }

    // Password validation (existing checks remain the same)
    if (empty($password)) {
        $errors[] = ["field" => "password", "message" => "Password is required."];
    } elseif (
        strlen($password) < 8 ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[0-9]{3,}/", $password) ||
        !preg_match("/[@#$%^&*!_]/", $password)
    ) {
        $errors[] = ["field" => "password", "message" => "Password must be at least 8 characters long, contain one uppercase letter, three digits, and a special character."];
    }

    if ($password !== $passwordConfirm) {
        $errors[] = ["field" => "confirm-password", "message" => "Passwords do not match."];
    }

    if (!in_array($userType, ['guest', 'owner'])) {
        $errors[] = ["field" => "user-type", "message" => "Invalid user type selected."];
    }

    // Check for duplicate email
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id FROM hb_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = ["field" => "email", "message" => "An account with this email already exists."];
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data including phone number
            $stmt = $conn->prepare("
                INSERT INTO hb_users (
                    first_name, 
                    last_name, 
                    email, 
                    phone_number,
                    password, 
                    user_type, 
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param(
                "ssssss",
                $firstName,
                $lastName,
                $email,
                $phone,
                $hashedPassword,
                $userType
            );

            if ($stmt->execute()) {
                // Store email in session for potential autofill on login
                $_SESSION['userEmail'] = $email;

                // Respond with success
                header('Content-Type: application/json');
                echo json_encode(["success" => true]);
                exit;
            } else {
                $errors[] = ["field" => "general", "message" => "Error creating account. Please try again."];
            }
        }
        $stmt->close();
    }

    // Output JSON response with errors if any
    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(["success" => false, "errors" => $errors]);
    }
    exit;
}
