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

    // Base query (ensure guests column is included)
    $query = "
        SELECT b.booking_id, h.hotel_name, r.room_type, b.check_in_date, b.check_out_date, b.total_price, b.status, b.guests
        FROM hb_bookings b
        JOIN hb_hotels h ON b.hotel_id = h.hotel_id
        JOIN hb_rooms r ON b.room_id = r.room_id
        WHERE b.user_id = ?
    ";



    // Append status condition if provided
    if ($status) {
        $query .= " AND b.status = ?";
    }

    $query .= " ORDER BY b.created_at DESC";

    $stmt = $conn->prepare($query);

    if ($status) {
        $stmt->bind_param("is", $userId, $status);
    } else {
        $stmt->bind_param("i", $userId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    return $bookings;
}
