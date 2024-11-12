<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'User Management System' ?></title>
</head>

<link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.tailwindcss.com"></script>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
  width: 100%;
  height: 100vh;
}  
</style>
<body>
<div class="navbar bg-base-100 shadow-lg">
  <div class="navbar-start">
    <a class="btn btn-ghost text-xl">User Management System</a>
  </div>
  <div class="navbar-end">
    <td class='flex gap-2 items-center'>
      <div class='me-3'>
          <img class='w-10 h-10 rounded-full' src="https://ui-avatars.com/api/?name=<?= htmlspecialchars($_SESSION['username']); ?>" />
      </div>
      <div class='me-2'><?= htmlspecialchars($_SESSION['username']); ?></div>
    </td>

  <form action="/logout" method="POST">
    <button type="submit" class="btn btn-neutral btn-sm">Logout</button>
</form>
  </div>
</div>

<div class="drawer lg:drawer-open">
  <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
  <div class="drawer-content flex flex-col items-center justify-center">
    <!-- Page content here -->
    <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">
      Open drawer
    </label>
  </div>
  <div class="drawer-side">
    <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
    <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
      <!-- Sidebar content here -->
      <li><a href='/users'>User lists</a></li>
      <li><a href='/users/create'>Create User</a></li>
      <li><a href='/roles'>Role lists</a></li>
      <li><a href='/roles/create'>Create Role</a></li>

    </ul>
  </div>

  <!-- Content Slot -->
  <div class="drawer-content ">
            <!-- Inject the content here -->
            <div class="w-full p-3 border border-base-300 rounded-lg ">
                <h1 class='text-center p-5'>Welcome to user management system</h1>
                <?php echo $content ?? ''; ?>
            </div>
  </div>
</div>
</body>
</html>