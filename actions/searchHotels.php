<?php
require "../functions/session_check.php";
require '../db/config.php';

$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

$query = "
    SELECT 
        h.hotel_id, 
        h.hotel_name, 
        h.location, 
        h.description, 
        MIN(r.price_per_night) AS min_price, 
        MAX(r.price_per_night) AS max_price,
        (
            SELECT hi.image_url 
            FROM hb_hotel_images hi 
            WHERE hi.hotel_id = h.hotel_id 
            LIMIT 1
        ) AS image_url
    FROM hb_hotels h
    JOIN hb_rooms r ON h.hotel_id = r.hotel_id
    WHERE h.availability = TRUE 
    AND (h.hotel_name LIKE ? OR h.location LIKE ?)
    GROUP BY h.hotel_id, h.hotel_name, h.location, h.description
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$hotels = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['image_url'] = !empty($row['image_url']) ? '../../' . $row['image_url'] : '../../assets/images/placeholder.jpg';
        $hotels[] = $row;
    }
}

echo json_encode(["success" => true, "hotels" => $hotels]);
?>