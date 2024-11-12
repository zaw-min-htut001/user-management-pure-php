<?php
$title = "Role lists";  

ob_start();
?>
<h1>Role lists</h1>
<table class='table table-zebra w-full'>
    <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($roles)): ?>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td><?= htmlspecialchars($role['name']); ?></td>
                    <td class='flex gap-2'>
                        <!-- Edit Form -->
                        <form  action="/role/edit" method="POST" >
                            <input type="hidden" name="id" value="<?= $role['id']; ?>">
                            <input class='cursor-pointer' type="submit" value="Edit">
                        </form> |
                        <!-- Delete Form -->
                        <form  action="/role/delete" method="POST" >
                            <input type="hidden" name="id" value="<?= $role['id']; ?>">
                            <input class='cursor-pointer' type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this role?');">
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

