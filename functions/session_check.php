<?php
// TODO: Add code to check user type and prohibit access to certain pages
// Only start a session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    // Redirect to login page if no active session
    header("Location: ../login.php");
    // if logged in and user_type is owner, redirect to the dashboard

    // if logged in and user_type is guest, redirect to the browse_hotels page
    // if logged in and user_type is owner, redirect to manage_hotel page
    exit;
}
