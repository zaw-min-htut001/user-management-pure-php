<?php
$title = "Create User";  // Set a specific title for this page

// Start output buffering to capture HTML content
ob_start();
?>
<h1>Create User</h1>
<div class="flex items-center justify-center">
        <div class="p-4 mt-5 bg-base-300 rounded-md shadow-md mx-5 max-w-xl w-full">
            <form  action="/user/store" method="POST">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input required id="name" name="name" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xl block" />
                </div>

                <div class="mb-3">
                    <label for="username">User Name</label>
                    <input required id="username" name="username" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xl block" />
                </div>

                <div class="mb-3">
                    <label for="role_id">Roles</label>
                    <select required name="role_id" id="role_id" class="input input-bordered w-full max-w-xl block"  >
                    <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input required id="email" name="email" type="email" placeholder="Type here email" class="input input-bordered w-full max-w-xl block" />
                </div>

                <div class="mb-3">
                    <label for="phone">Phone</label>
                    <input required id="phone" name="phone" type="number" placeholder="Type here phone" class="input input-bordered w-full max-w-xl block" />
                </div>

                <div class="mb-3">
                    <label for="address">Address</label>
                    <input required id="address" name="address" type="text" placeholder="Type here address" class="input input-bordered w-full max-w-xl block" />
                </div>

                <div class="mb-3">
                    <label class='me-3' for="is_active">Is Active</label>
                    <input required id="is_active" name="is_active" type="checkbox"  />
                </div>

                <div class="mb-3">
                    <label for="gender">Gender</label>
                    <select required name="gender" id="gender" class="input input-bordered w-full max-w-xl block"  >
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password">Password</label>
                    <input required id="password" name="password" type="password" placeholder="Type here password" class="input input-bordered w-full max-w-xl block" />
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
