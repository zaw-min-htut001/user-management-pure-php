<?php
$title = "User Details";  // Set a specific title for this page

// Start output buffering to capture HTML content
ob_start();
?>
<h1>User Details</h1>

<div class="flex items-center justify-center">
        <div class="p-4 mt-5 bg-base-300 rounded-md shadow-md mx-5 max-w-xl w-full">
        <div class="leading-10">
            <div class='flex justify-between'>
                <p><strong>Name:</strong></p><span class='text-end'><?= htmlspecialchars($user['name']); ?></span>
            </div>
            <div class='flex justify-between'>
                <p><strong>Username:</strong></p><span><?= htmlspecialchars($user['username']); ?></span>
            </div>

            <div class='flex justify-between'>
                <p><strong>Email:</strong></p> <span><?= htmlspecialchars($user['email']); ?></span>
            </div>

            <div class='flex justify-between'>
                <p><strong>Phone:</strong> </p> <span><?= htmlspecialchars($user['phone']); ?></span>
            </div>

            <div class='flex justify-between'>
                <p><strong>Address:</strong> </p> <span><?= htmlspecialchars($user['address']); ?></span>
            </div>

            <div class='flex justify-between'>
                <p><strong>Gender:</strong></p> <span><?= htmlspecialchars($user['gender']); ?></span>
            </div>

            <div class='flex justify-between'>
                <p><strong>Status:</strong></p> <span><?= $user['is_active'] ? 'Active' : 'Inactive'; ?></span>
            </div>

            <div class='flex justify-between'>
                <p><strong>Role:</strong></p> <span><?= htmlspecialchars($user['role_name']); ?></span>
            </div>

        </div>
    </div>
<?php
// Capture the output and store it in $content
$content = ob_get_clean();

// Include the app.php layout or base view with the title and content
require_once BASE_PATH . '/src/views/app.php';

