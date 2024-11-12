<?php
$title = "Edit Role";  // Set a specific title for this page

// Start output buffering to capture HTML content
ob_start();
?>
<h1>Edit Role</h1>

<div class="flex items-center justify-center">
    <div class="p-4 mt-5 bg-base-300 rounded-md shadow-md mx-5 max-w-xl w-full">
        <form action="/role/update" method="POST">
        <input type="hidden" name="id" value="<?= $role['id']; ?>">

            <div class="mb-3">
                <label for="name">Name</label>
                <input required id="name" name="name" type="text" value="<?= htmlspecialchars($role['name']) ?>" class="input input-bordered w-full max-w-xl" />
            </div>

            <div>
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Create</th>
                            <th>Read</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                        <tbody>
                        <?php foreach ($permissions as $featureId => $feature): ?>
                            <tr>
                                <td><?= htmlspecialchars($feature['feature_name']) ?></td>
                                    <?php foreach ($feature['permissions'] as $permissionId => $permission): ?>
                                        <?php
                                            // Check if the user has this permission
                                            $hasPermission = in_array($permission['permission_id'], $userPermissions[$featureId] ?? []);
                                        ?>
                                        <td>
                                        <input type="checkbox" name="permissions[<?php echo $featureId; ?>][]" 
                                                    value="<?php echo $permission['permission_id']; ?>" 
                                                    <?php echo $hasPermission ? 'checked' : ''; ?>>
                                </td>
                                    <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                     
                </table>
            </div>

            <div class="mb-3 flex justify-end">
                <button type="submit" class="btn btn-neutral btn-md w-32">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once BASE_PATH . '/src/views/app.php';
