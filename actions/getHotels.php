<?php
require '../db/config.php';

try {
    $stmt = $conn->prepare("SELECT hotel_id, hotel_name, location, description FROM hb_hotels WHERE availability = TRUE");
    $stmt->execute();
    $result = $stmt->get_result();

    $hotels = [];
    while ($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }

    echo json_encode(["success" => true, "hotels" => $hotels]);
    exit;
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error fetching hotels: " . $e->getMessage()]);
    exit;
}
?>
