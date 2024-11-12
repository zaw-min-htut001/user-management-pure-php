
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

</style>
<body class=''>
<div class="grid place-items-center h-[100vh]">
        <div class="p-4 mt-5 bg-base-300 rounded-md shadow-md mx-5 max-w-xl w-full">
            <form  action="/login" method="POST">

                <div class="mb-3">
                    <label for="username">Username</label>
                    <input required id="username" name="username" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xl block" />
                </div>

                <div class="mb-3">
                    <label for="password">Password</label>
                    <input required id="password" name="password" type="password" placeholder="Type here password" class="input input-bordered w-full max-w-xl block" />
                </div>

                <div class="mb-3 flex justify-end">
                    <button type="submit" class="btn btn-neutral btn-md w-full">Login</button>
                </div>
            </form>
        </div>
</body>
</html>