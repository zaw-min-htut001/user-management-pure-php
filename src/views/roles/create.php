<?php
$title = "Create Role";  // Set a specific title for this page

// Start output buffering to capture HTML content
ob_start();
?>
<h1>Create Role</h1>

<div class="flex items-center justify-center">
        <div class="p-4 mt-5 bg-base-300 rounded-md shadow-md mx-5 max-w-xl w-full">
            <form  action="/role/store" method="POST">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input required id="name" name="name" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xl block" />
                </div>

                <div>
                <table  class='table table-zebra w-full'>
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Create</th>
                            <th>Read</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($features as $feature_id => $feature_data): ?>
                            <tr>
                                <td><?= htmlspecialchars($feature_data['feature_name']) ?></td>
                                <td>
                                    <input type="checkbox" name="permissions[<?= $feature_id ?>][create]">
                                </td>
                                <td>
                                    <input type="checkbox" name="permissions[<?= $feature_id ?>][read]">
                                </td>
                                <td>
                                    <input type="checkbox" name="permissions[<?= $feature_id ?>][update]">
                                </td>
                                <td>
                                    <input type="checkbox" name="permissions[<?= $feature_id ?>][delete]">
                                </td>
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
// Capture the output and store it in $content
$content = ob_get_clean();

// Include the app.php layout or base view with the title and content
require_once BASE_PATH . '/src/views/app.php';

