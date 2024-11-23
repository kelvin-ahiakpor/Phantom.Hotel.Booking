<?php
require "../functions/session_check.php";
require '../db/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['userId'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    header("Location: ../view/login.php");
    exit;
}

$userId = $_SESSION['userId'];

try {
    $stmt = $conn->prepare("
    SELECT 
        b.booking_id, 
        h.hotel_name, 
        r.room_type, 
        b.check_in_date, 
        b.check_out_date, 
        b.total_price, 
        b.status, 
        (
            SELECT hi.image_url 
            FROM hb_hotel_images hi 
            WHERE hi.hotel_id = h.hotel_id 
            LIMIT 1
        ) AS image_url
    FROM hb_bookings b
    JOIN hb_hotels h ON b.hotel_id = h.hotel_id
    JOIN hb_rooms r ON b.room_id = r.room_id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
    ");

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $row['image_url'] = !empty($row['image_url']) ? '../../' . $row['image_url'] : '../../assets/images/placeholder.jpg';
        $bookings[] = $row;
    }
    

    echo json_encode(["success" => true, "bookings" => $bookings]);
    exit;
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error fetching bookings: " . $e->getMessage()]);
    exit;
}
?>