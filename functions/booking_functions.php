<?php
require_once '../db/config.php'; // Ensure database connection is available

/**
 * Fetch bookings based on user ID and booking status.
 *
 * @param int $userId
 * @param string|null $status (optional)
 * @return array
 */
function getBookingsByStatus($userId, $status = null)
{
    global $conn;

    $query = "
        SELECT 
            b.booking_id, 
            h.hotel_name, 
            r.room_type, 
            b.check_in_date, 
            b.check_out_date, 
            b.total_price, 
            b.status, 
            b.guests, 
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
    ";

    if ($status && strtolower($status) !== 'all') {
        $query .= " AND b.status = ?";
    }

    $query .= " ORDER BY b.created_at DESC";

    $stmt = $conn->prepare($query);
    if ($status && strtolower($status) !== 'all') {
        $stmt->bind_param("is", $userId, $status);
    } else {
        $stmt->bind_param("i", $userId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $row['image_url'] = !empty($row['image_url']) ? '../../' . $row['image_url'] : '../../assets/images/placeholder.jpg';
        $bookings[] = $row;
    }

    return $bookings;
}