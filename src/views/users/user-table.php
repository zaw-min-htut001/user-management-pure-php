<?php
$title = "User List";  // Set a specific title for this page

// Start output buffering to capture HTML content
ob_start();
?>
<h1>User List</h1>
<table class='table table-zebra w-full'>
    <thead>
        <tr>
            <th>User Name</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class='flex gap-2 items-center'>
                        <div>
                            <img class='w-10 h-10 rounded-full' src="https://ui-avatars.com/api/?name=<?= htmlspecialchars($user['name']); ?>" />
                        </div>
                        <div><?= htmlspecialchars($user['username']); ?></div>
                    </td>
                    <td><?= htmlspecialchars($user['name']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= htmlspecialchars($user['role_name']); ?></td>
                    <td class='flex gap-2'>
                        <!-- Edit Form -->
                        <form  action="/user/edit" method="POST" >
                            <input type="hidden" name="id" value="<?= $user['id']; ?>">
                            <input class='cursor-pointer' type="submit" value="Edit">
                        </form> |
                        <!-- Delete Form -->
                        <form  action="/user/delete" method="POST" >
                            <input type="hidden" name="id" value="<?= $user['id']; ?>">
                            <input class='cursor-pointer' type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                        </form> | 
                        <form  action="/user/show" method="POST" >
                            <input type="hidden" name="id" value="<?= $user['id']; ?>">
                            <input class='cursor-pointer' type="submit" value="Show">
                        </form> 
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No users found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
// Capture the output and store it in $content
$content = ob_get_clean();

// Include the app.php layout or base view with the title and content
require_once BASE_PATH . '/src/views/app.php';

