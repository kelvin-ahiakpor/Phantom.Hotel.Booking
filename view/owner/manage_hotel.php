<?php
session_start();
require_once '../../db/config.php';
require_once '../../middleware/checkUserAccess.php';
checkUserAccess('owner');

try {
    // Check if owner has a hotel
    $ownerId = $_SESSION['userId'];
    $stmt = $conn->prepare("SELECT COUNT(*) as hotel_count FROM hb_hotels WHERE owner_id = ?");
    $stmt->bind_param("i", $ownerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $hasHotel = $result->fetch_assoc()['hotel_count'] > 0;

    // Get hotel details if exists
    $hotelDetails = null;
    $hotelImages = [];
    $recentBookings = null;

    if ($hasHotel) {
        // Get hotel details with statistics
        $hotelStmt = $conn->prepare(
            "
            SELECT h.*, 
                   COUNT(DISTINCT b.booking_id) as total_bookings,
                   SUM(CASE WHEN b.check_out_date >= CURDATE() THEN 1 ELSE 0 END) as active_bookings,
                   COALESCE(SUM(p.amount), 0) as total_revenue
            FROM hb_hotels h
            LEFT JOIN hb_bookings b ON h.hotel_id = b.hotel_id
            LEFT JOIN hb_payments p ON b.booking_id = p.booking_id AND p.payment_status = 'completed'
            WHERE h.owner_id = ?
            GROUP BY h.hotel_id"
        );
        $hotelStmt->bind_param("i", $ownerId);
        $hotelStmt->execute();
        $hotelDetails = $hotelStmt->get_result()->fetch_assoc();

        if ($hotelDetails) {
            // Get hotel images
            $imageStmt = $conn->prepare(
                "
                SELECT image_url 
                FROM hb_hotel_images 
                WHERE hotel_id = ? 
                LIMIT 3"
            );
            $imageStmt->bind_param("i", $hotelDetails['hotel_id']);
            $imageStmt->execute();
            $imageResult = $imageStmt->get_result();
            while ($image = $imageResult->fetch_assoc()) {
                $hotelImages[] = $image['image_url'];
            }

            // Get recent bookings
            $bookingsStmt = $conn->prepare(
                "
                SELECT 
                    b.booking_id,
                    CONCAT(u.first_name, ' ', u.last_name) as guest_name,
                    b.check_in_date,
                    b.check_out_date,
                    p.payment_status,
                    b.total_price
                FROM hb_bookings b
                JOIN hb_users u ON b.user_id = u.user_id
                LEFT JOIN hb_payments p ON b.booking_id = p.booking_id
                WHERE b.hotel_id = ?
                ORDER BY b.created_at DESC
                LIMIT 5"
            );
            $bookingsStmt->bind_param("i", $hotelDetails['hotel_id']);
            $bookingsStmt->execute();
            $recentBookings = $bookingsStmt->get_result();
        }
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    $hasHotel = false;
    $hotelDetails = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Phantom - Hotel Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="text-2xl font-serif text-gray-800">Phantom</div>
            <div class="flex items-center space-x-4">
                <?php if (!$hasHotel): ?>
                    <a href="./create_hotel.php" class="bg-black text-white px-4 py-2  hover:bg-gray-800 transition">
                        Create Hotel
                    </a>
                <?php endif; ?>
                <button class="text-gray-600 hover:text-gray-800" id="profileBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Profile Modal -->
    <div id="profileModal" class="hidden fixed top-14 right-4 bg-white rounded-lg shadow-lg p-6 z-20">
        <div class="flex flex-col space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium"><?php echo htmlspecialchars($_SESSION['firstName']); ?></h3>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                </div>

                <button id="closeProfileModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <hr>
            <a href="../../view/manage_account.php" class="text-blue-600 hover:text-blue-700">Manage Account</a>
            <a href="../../actions/logout.php" class="text-red-600 hover:text-red-700">Logout</a>
        </div>
    </div>



    <main class="min-h-screen container mx-auto px-4 py-8">
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h1 class="text-3xl font-bold mb-6">Hotel Management Dashboard</h1>

            <?php if ($hasHotel && $hotelDetails): ?>
                <div class="space-y-6">
                    <div class="border-b pb-6">
                        <h2 class="text-2xl font-semibold mb-4">Your Hotel</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Hotel Images Section -->
                            <div class="space-y-4">
                                <!-- Main Image -->
                                <div class="relative w-full h-64 rounded-lg overflow-hidden">
                                    <img

                                        src="<?php echo !empty($hotelImages) ? "../../" . htmlspecialchars($hotelImages[0]) : '../../assets/images/default-hotel.jpg'; ?>"
                                        alt="<?php echo htmlspecialchars($hotelDetails['hotel_name']); ?>"
                                        class="w-full h-full object-cover hotel-image" />
                                </div>

                                <!-- Additional Images -->
                                <?php if (count($hotelImages) > 1): ?>
                                    <div class="grid grid-cols-<?php echo count($hotelImages) - 1; ?> gap-4">
                                        <?php for ($i = 1; $i < count($hotelImages); $i++): ?>
                                            <div class="relative w-full h-32 rounded-lg overflow-hidden">
                                                <img
                                                    src="../../<?php echo htmlspecialchars($hotelImages[$i]); ?>"
                                                    alt="Additional view of <?php echo htmlspecialchars($hotelDetails['hotel_name']); ?>"
                                                    class="w-full h-full object-cover hotel-image" />
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Hotel Details -->
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold"><?php echo htmlspecialchars($hotelDetails['hotel_name']); ?></h3>
                                <p class="text-gray-600"><?php echo htmlspecialchars($hotelDetails['description']); ?></p>
                                <p class="text-gray-600"><strong>Location:</strong> <?php echo htmlspecialchars($hotelDetails['location']); ?></p>
                                <p class="text-gray-600"><strong>Address:</strong> <?php echo htmlspecialchars($hotelDetails['address']); ?></p>
                                <div class="flex space-x-4">
                                    <a href="edit_hotel.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                        Edit Hotel
                                    </a>
                                    <a href="manage_room.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                                        Manage Rooms
                                    </a>
                                    <button onclick="showDeleteModal()"
                                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                        Delete Hotel
                                    </button>
                                    <!-- Delete Modal HTML -->
                                    <div id="deleteModal"
                                        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                                        <!-- Modal content -->
                                        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 relative">
                                            <h3 class="text-2xl font-bold mb-4">Delete Hotel</h3>
                                            <p class="text-gray-600 mb-6">
                                                Are you sure you want to delete "<?php echo htmlspecialchars($hotelDetails['hotel_name']); ?>"?
                                                This action cannot be undone and will remove all associated data including bookings and reviews.
                                            </p>
                                            <div class="flex justify-end space-x-4">
                                                <button onclick="hideDeleteModal()"
                                                    class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 transition">
                                                    Cancel
                                                </button>
                                                <button onclick="deleteHotel(<?php echo $hotelDetails['hotel_id']; ?>)"
                                                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                                    Delete Hotel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Total Bookings</h3>
                            <p class="text-3xl font-bold"><?php echo $hotelDetails['total_bookings']; ?></p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Active Bookings</h3>
                            <p class="text-3xl font-bold"><?php echo $hotelDetails['active_bookings']; ?></p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Total Revenue</h3>
                            <p class="text-3xl font-bold">$<?php echo number_format($hotelDetails['total_revenue'], 2); ?></p>
                        </div>
                    </div>


                    <!-- Recent Bookings -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-semibold">Recent Bookings</h2>
                            <a href="view_bookings.php" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                                View All Bookings
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b text-left">Guest</th>
                                        <th class="px-6 py-3 border-b text-left">Check-in</th>
                                        <th class="px-6 py-3 border-b text-left">Check-out</th>
                                        <th class="px-6 py-3 border-b text-left">Status</th>
                                        <th class="px-6 py-3 border-b text-left">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($recentBookings && $recentBookings->num_rows > 0): ?>
                                        <?php while ($booking = $recentBookings->fetch_assoc()): ?>
                                            <tr>
                                                <td class="px-6 py-4 border-b"><?php echo htmlspecialchars($booking['guest_name']); ?></td>
                                                <td class="px-6 py-4 border-b"><?php echo htmlspecialchars($booking['check_in_date']); ?></td>
                                                <td class="px-6 py-4 border-b"><?php echo htmlspecialchars($booking['check_out_date']); ?></td>
                                                <td class="px-6 py-4 border-b">
                                                    <span class="px-2 py-1 rounded <?php echo $booking['payment_status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                        <?php echo ucfirst(htmlspecialchars($booking['payment_status'])); ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 border-b">$<?php echo number_format($booking['total_price'], 2); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td class="px-6 py-4 border-b text-center" colspan="5">No bookings found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <h2 class="text-2xl font-semibold mb-4">Welcome to Phantom</h2>
                    <p class="text-gray-600 mb-6">You haven't created a hotel yet. Start by creating your first hotel.</p>
                    <a href="./create_hotel.php" class="bg-black text-white px-8 py-3 hover:bg-gray-800 transition">
                        Create Your Hotel
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="text-sm">© 2024 Phantom Booking, L.L.C. All rights reserved.</div>
            <div class="space-x-4">
                <a href="#" class="hover:text-gray-400">Terms of Use</a>
                <a href="#" class="hover:text-gray-400">BEIAN CN Site</a>
                <a href="#" class="hover:text-gray-400">Do Not Sell Information</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // const profileBtn = document.getElementById('profile-btn');
            // const profileModal = document.getElementById('profile-modal');
            // const closeModal = document.getElementById('close-modal');

            const elements = {
                profileBtn: document.getElementById('profileBtn'),
                profileModal: document.getElementById('profileModal'),
                closeProfileBtn: document.getElementById('closeProfileModal'),
            };

            // profileBtn.addEventListener('click', () => {
            //     profileModal.classList.toggle('hidden');
            // });

            // closeModal.addEventListener('click', () => {
            //     profileModal.classList.add('hidden');
            // });


            // window.addEventListener('click', (e) => {
            //     if (!profileModal.contains(e.target) && !profileBtn.contains(e.target)) {
            //         profileModal.classList.add('hidden');
            //     }
            // });


            // Profile Modal Functionality
            if (elements.profileBtn && elements.profileModal && elements.closeProfileBtn) {
                elements.profileBtn.addEventListener('click', () => {
                    elements.profileModal.classList.toggle('hidden');
                });

                elements.closeProfileBtn.addEventListener('click', () => {
                    elements.profileModal.classList.add('hidden');
                });

                // Close modal when clicking outside
                document.addEventListener('click', (e) => {
                    if (!elements.profileModal.contains(e.target) && !elements.profileBtn.contains(e.target)) {
                        elements.profileModal.classList.add('hidden');
                    }
                });
            }

            window.showDeleteModal = function() {
                document.getElementById('deleteModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }

            window.hideDeleteModal = function() {
                document.getElementById('deleteModal').classList.add('hidden');
                document.body.style.overflow = ''; // Restore scrolling
            }

            window.deleteHotel = async function(hotelId) {
                try {
                    const response = await fetch('../../actions/deleteHotel.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            hotelId: hotelId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Show success message
                        showNotification('Hotel deleted successfully', 'success');
                        // Redirect after a short delay
                        setTimeout(() => {
                            window.location.href = 'manage_hotel.php';
                        }, 1500);
                    } else {
                        showNotification(data.message || 'Failed to delete hotel', 'error');
                    }
                } catch (error) {
                    showNotification('An error occurred while deleting the hotel', 'error');
                } finally {
                    hideDeleteModal();
                }
            }

            // Notification function
            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 
                ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                notification.textContent = message;
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
            }

            // Close modal on outside click
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideDeleteModal();
                }
            });


        });
    </script>
</body>

</html>