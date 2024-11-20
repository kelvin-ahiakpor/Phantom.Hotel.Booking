<?php
session_start();
require '../db/config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"]);
    $passwordConfirm = trim($_POST["password-confirm"]);
    $agreeTerms = isset($_POST["terms"]) ? 1 : 0;

    // Validation checks
    if (empty($email)) {
        $errors[] = ["field" => "email", "message" => "Email is required."];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = ["field" => "email", "message" => "Invalid email format."];
    }

    if (empty($password)) {
        $errors[] = ["field" => "password", "message" => "Password is required."];
    } elseif (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]{3,}/", $password) || !preg_match("/[@#$%^&*!_]/", $password)) {
        $errors[] = ["field" => "password", "message" => "Password must be at least 8 characters long, contain one uppercase letter, 3 digits, and a special character."];
    }

    if ($password !== $passwordConfirm) {
        $errors[] = ["field" => "password-confirm", "message" => "Passwords do not match."];
    }

    if (!$agreeTerms) {
        $errors[] = ["field" => "terms", "message" => "You must agree to the terms and conditions."];
    }

    // Check for duplicate email in the database
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id FROM rs_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = ["field" => "email", "message" => "An account with this email already exists."];
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $stmt = $conn->prepare("INSERT INTO rs_users (email, password, role, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $role = 2; // Example role
            $stmt->bind_param("ssi", $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                // Store email in session for autofill on login
                $_SESSION['userEmail'] = $email;

                // Respond with success
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
        echo json_encode(["success" => false, "errors" => $errors]);
    }
    exit;
}
