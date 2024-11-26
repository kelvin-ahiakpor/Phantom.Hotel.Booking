<?php
session_start();
require_once '../db/config.php';
require_once '../functions/session_check.php';

try {
    // Get user details
    $userId = $_SESSION['userId'];
    $stmt = $conn->prepare("
        SELECT user_id, first_name, last_name, email, phone_number, user_type 
        FROM hb_users 
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $userDetails = $stmt->get_result()->fetch_assoc();

    if (!$userDetails) {
        header("Location: ../auth/login.php");
        exit;
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account | Phantom</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-text {
            display: none;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .loading-spinner {
            display: none;
        }

        .modal {
            transition: opacity 0.25s ease;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm fixed w-full z-10">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <?php if ($userDetails['user_type'] === 'owner'): ?>
                <a href="manage_hotel.php" class="text-2xl font-serif text-gray-800">Phantom</a>
            <?php else: ?>
                <a href="../dashboard.php" class="text-2xl font-serif text-gray-800">Phantom</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container mx-auto px-4 pt-20 pb-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold">Manage Account</h1>
                    <?php if ($userDetails['user_type'] === 'owner'): ?>
                        <a href="../view/owner/manage_hotel.php" class="text-gray-600 hover:text-gray-800">
                            ← Back to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="../dashboard.php" class="text-gray-600 hover:text-gray-800">
                            ← Back to Dashboard
                        </a>
                    <?php endif; ?>
                </div>

                <form id="accountForm" class="space-y-6">
                    <input type="hidden" name="userId" value="<?php echo htmlspecialchars($userDetails['user_id']); ?>">

                    <!-- First Name -->
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="firstName" name="firstName"
                            value="<?php echo htmlspecialchars($userDetails['first_name']); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                            required>
                        <div id="firstNameError" class="error-text"></div>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="lastName" name="lastName"
                            value="<?php echo htmlspecialchars($userDetails['last_name']); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                            required>
                        <div id="lastNameError" class="error-text"></div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo htmlspecialchars($userDetails['email']); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                            required>
                        <div id="emailError" class="error-text"></div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phoneNumber" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phoneNumber" name="phoneNumber"
                            value="<?php echo htmlspecialchars($userDetails['phone_number']); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                        <div id="phoneError" class="error-text"></div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium mb-4">Change Password</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="currentPassword" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" id="currentPassword" name="currentPassword"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                                <div id="currentPasswordError" class="error-text"></div>
                            </div>
                            <div>
                                <label for="newPassword" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" id="newPassword" name="newPassword"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                                <div id="newPasswordError" class="error-text"></div>
                            </div>
                            <div>
                                <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" id="confirmPassword" name="confirmPassword"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                                <div id="confirmPasswordError" class="error-text"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-6">
                        <button type="button" id="deleteAccountBtn"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Delete Account
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-black text-white rounded-md hover:bg-gray-800 transition flex items-center">
                            <span>Save Changes</span>
                            <div class="loading-spinner ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Delete Account Confirmation Modal -->
    <div id="deleteModal" class="modal fixed inset-0 hidden z-50">
        <div class="min-h-screen px-4 text-center">
            <div class="modal-backdrop fixed inset-0" aria-hidden="true"></div>
            <div class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Delete Account</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Are you sure you want to delete your account? This action cannot be undone and will permanently delete all your data.
                </p>

                <form id="deleteAccountForm" class="space-y-4">
                    <div>
                        <label for="deletePassword" class="block text-sm font-medium text-gray-700">
                            Please enter your password to confirm
                        </label>
                        <input type="password" id="deletePassword" name="deletePassword" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                        <div id="deletePasswordError" class="error-text"></div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelDeleteBtn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            Delete Account
                            <span class="loading-spinner hidden ml-2">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="module" src="../assets/js/manage_account.js"></script>
</body>

</html>