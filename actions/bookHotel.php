<?php
session_start();
require "../db/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['userId']; // Assuming user ID is stored in session
    $hotel_id = intval($_POST['hotel_id']);
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $guests = intval($_POST['guests']);

    // Fetch hotel price per night
    $query = "SELECT price_per_night FROM hb_hotels WHERE hotel_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Hotel not found.");
    }

    $hotel = $result->fetch_assoc();
    $price_per_night = $hotel['price_per_night'];

    // Calculate total price
    $nights = (new DateTime($check_out_date))->diff(new DateTime($check_in_date))->days;
    $total_price = $nights * $price_per_night;

    // Insert booking into database
    $query = "INSERT INTO hb_bookings (user_id, hotel_id, room_id, check_in_date, check_out_date, total_price)
              VALUES (?, ?, NULL, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissd", $user_id, $hotel_id, $check_in_date, $check_out_date, $total_price);

    if ($stmt->execute()) {
        header("Location: ../view/user/bookings.php?success=true");
    } else {
        die("Booking failed: " . $conn->error);
    }
}
?>
