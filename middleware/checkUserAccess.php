<?php
// Place this file in a middleware or includes folder
function checkUserAccess($allowedUserType)
{
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['userId'])) {
        header("Location: ../view/login.php");
        exit;
    }

    // Check if user type matches allowed type
    if ($_SESSION['userType'] !== $allowedUserType) {
        // Redirect based on user type
        if ($_SESSION['userType'] === 'owner') {
            header("Location: ../view/manage_hotel.php");
        } else {
            header("Location: ../view/hotel_feed.php");
        }
        exit;
    }
}
