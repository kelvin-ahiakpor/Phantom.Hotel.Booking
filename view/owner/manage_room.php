<?php
session_start();
require_once '../../db/config.php';
require_once '../../middleware/checkUserAccess.php';
checkUserAccess('owner');

try {
    // Get hotel ID for the logged-in owner
    $ownerId = $_SESSION['userId'];
    $stmt = $conn->prepare("
        SELECT hotel_id, hotel_name 
        FROM hb_hotels B
        WHERE owner_id = ? 
        LIMIT 1
    ");
    $stmt->bind_param("i", $ownerId);
    $stmt->execute();
    $hotelResult = $stmt->get_result()->fetch_assoc();

    if (!$hotelResult) {
        header("Location: manage_hotel.php");
        exit;
    }

    // Get rooms for this hotel
    $hotelId = $hotelResult['hotel_id'];
    $stmt = $conn->prepare("
        SELECT r.*,
               COUNT(DISTINCT b.booking_id) as total_bookings,
               SUM(CASE WHEN b.check_out_date >= CURDATE() THEN 1 ELSE 0 END) as active_bookings
        FROM hb_rooms r
        LEFT JOIN hb_bookings b ON r.room_id = b.room_id
        WHERE r.hotel_id = ?
        GROUP BY r.room_id
        ORDER BY r.room_type ASC
    ");
    $stmt->bind_param("i", $hotelId);
    $stmt->execute();
    $rooms = $stmt->get_result();
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    header("Location: manage_hotel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms | Phantom</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .modal {
            transition: opacity 0.25s ease;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .error-text {
            display: none;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="manage_hotel.php" class="text-2xl font-serif text-gray-800">Phantom</a>
            <div class="flex items-center space-x-4">
                <button id="profileBtn" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-user"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Profile Modal -->
    <div id="profileModal" class="hidden fixed top-14 right-4 bg-white rounded-lg shadow-lg p-6 z-20">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-medium"><?php echo htmlspecialchars($_SESSION['firstName']); ?></h3>
            <button id="closeProfileModal" class="text-gray-400 hover:text-gray-500">×</button>
        </div>
        <div class="space-y-2">
            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <a href="../../actions/logout.php" class="block text-red-600 hover:text-red-700">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold">Manage Rooms</h1>
                        <p class="text-gray-600">
                            <?php echo htmlspecialchars($hotelResult['hotel_name']); ?>
                        </p>
                    </div>
                    <button id="addRoomBtn" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                        Add New Room
                    </button>
                </div>

                <!-- Rooms Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Night</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($rooms && $rooms->num_rows > 0): ?>
                                <?php while ($room = $rooms->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($room['room_type']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                <?php echo htmlspecialchars($room['capacity']); ?> Guests
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                $<?php echo number_format($room['price_per_night'], 2); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo $room['availability'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo $room['availability'] ? 'Available' : 'Unavailable'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                <?php echo $room['active_bookings']; ?> Active / <?php echo $room['total_bookings']; ?> Total
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3 edit-room-btn"
                                                data-room-id="<?php echo $room['room_id']; ?>"
                                                data-room-type="<?php echo htmlspecialchars($room['room_type']); ?>"
                                                data-capacity="<?php echo $room['capacity']; ?>"
                                                data-price="<?php echo $room['price_per_night']; ?>"
                                                data-availability="<?php echo $room['availability']; ?>">
                                                Edit
                                            </button>
                                            <?php if ($room['total_bookings'] == 0): ?>
                                                <button class="text-red-600 hover:text-red-900 delete-room-btn"
                                                    data-room-id="<?php echo $room['room_id']; ?>">
                                                    Delete
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No rooms found. Click "Add New Room" to create your first room.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Add/Edit Room Modal -->
    <div id="roomModal" class="modal fixed inset-0 hidden z-50">
        <div class="min-h-screen px-4 text-center">
            <div class="modal-backdrop fixed inset-0" aria-hidden="true"></div>
            <div class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <button type="button" id="closeRoomModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>

                <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Add New Room</h3>

                <form id="roomForm" class="space-y-4">
                    <input type="hidden" id="roomId" name="roomId">
                    <input type="hidden" id="hotelId" name="hotelId" value="<?php echo $hotelId; ?>">

                    <!-- Room Type -->
                    <div>
                        <label for="roomType" class="block text-sm font-medium text-gray-700">Room Type</label>
                        <input type="text" id="roomType" name="roomType" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black">
                        <div id="roomTypeError" class="error-text"></div>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity (Guests)</label>
                        <input type="number" id="capacity" name="capacity" required min="1"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black">
                        <div id="capacityError" class="error-text"></div>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="pricePerNight" class="block text-sm font-medium text-gray-700">Price per Night ($)</label>
                        <input type="number" id="pricePerNight" name="pricePerNight" required min="0" step="0.01"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black">
                        <div id="priceError" class="error-text"></div>
                    </div>

                    <!-- Availability -->
                    <div class="flex items-center">
                        <input type="checkbox" id="availability" name="availability"
                            class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                        <label for="availability" class="ml-2 block text-sm text-gray-900">
                            Room is available for booking
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-5 flex justify-end space-x-3">
                        <button type="button" id="cancelRoomBtn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-black rounded-md hover:bg-gray-800">
                            <span id="submitBtnText">Add Room</span>
                            <span class="loading-spinner hidden ml-2">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal fixed inset-0 hidden z-50">
        <div class="min-h-screen px-4 text-center">
            <div class="modal-backdrop fixed inset-0" aria-hidden="true"></div>
            <div class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Delete Room</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Are you sure you want to delete this room? This action cannot be undone.
                </p>

                <form id="deleteRoomForm" class="flex justify-end space-x-3">
                    <input type="hidden" id="deleteRoomId" name="roomId">

                    <button type="button" id="cancelDeleteBtn"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Delete Room
                        <span class="loading-spinner hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script type="module" src="../../assets/js/manage_room.js"></script>
</body>

</html>