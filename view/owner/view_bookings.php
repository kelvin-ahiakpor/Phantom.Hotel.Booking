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
        FROM hb_hotels 
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

    // Pagination setup
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10; // Items per page
    $offset = ($page - 1) * $limit;

    // Get total bookings count
    $countStmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM hb_bookings b
        WHERE b.hotel_id = ?
    ");
    $countStmt->bind_param("i", $hotelResult['hotel_id']);
    $countStmt->execute();
    $totalBookings = $countStmt->get_result()->fetch_assoc()['total'];
    $totalPages = ceil($totalBookings / $limit);

    // Get bookings with pagination
    $bookingsStmt = $conn->prepare("
        SELECT 
            b.booking_id,
            b.room_id,
            r.room_type,
            CONCAT(u.first_name, ' ', u.last_name) as guest_name,
            u.email as guest_email,
            b.check_in_date,
            b.check_out_date,
            b.total_price,
            b.created_at,
            p.payment_status
        FROM hb_bookings b
        JOIN hb_users u ON b.user_id = u.user_id
        JOIN hb_rooms r ON b.room_id = r.room_id
        LEFT JOIN hb_payments p ON b.booking_id = p.booking_id
        WHERE b.hotel_id = ?
        ORDER BY b.created_at DESC
        LIMIT ? OFFSET ?
    ");
    $bookingsStmt->bind_param("iii", $hotelResult['hotel_id'], $limit, $offset);
    $bookingsStmt->execute();
    $bookings = $bookingsStmt->get_result();
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
    <title>All Bookings | Phantom</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
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

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">All Bookings</h1>
                    <p class="text-gray-600"><?php echo htmlspecialchars($hotelResult['hotel_name']); ?></p>
                </div>
                <a href="manage_hotel.php" class="text-gray-600 hover:text-gray-800">
                    ← Back to Dashboard
                </a>
            </div>

            <!-- Bookings Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booked On</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($bookings && $bookings->num_rows > 0): ?>
                            <?php while ($booking = $bookings->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        #<?php echo htmlspecialchars($booking['booking_id']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($booking['guest_name']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($booking['guest_email']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo htmlspecialchars($booking['room_type']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo date('M d, Y', strtotime($booking['check_in_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo date('M d, Y', strtotime($booking['check_out_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        $<?php echo number_format($booking['total_price'], 2); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $booking['payment_status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo ucfirst(htmlspecialchars($booking['payment_status'])); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo date('M d, Y H:i', strtotime($booking['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No bookings found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-6 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                Previous
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $page === $i ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?>"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                Next
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Profile Modal Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileBtn = document.getElementById('profileBtn');
            const profileModal = document.getElementById('profileModal');
            const closeProfileModal = document.getElementById('closeProfileModal');

            profileBtn.addEventListener('click', () => {
                profileModal.classList.toggle('hidden');
            });

            closeProfileModal.addEventListener('click', () => {
                profileModal.classList.add('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!profileModal.contains(e.target) && !profileBtn.contains(e.target)) {
                    profileModal.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>