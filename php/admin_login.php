<?php
/*******w*******
 * Name: Khushleen Kaur
 * Date: Nov 22, 2024
 * Description: Admin login page with email and password form.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
include('navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <header>
        <h1>Admin Login</h1>
    </header>

    <div class="login-form-container">
        <form action="admin_authenticate.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" auttocomplete="email" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" auttocomplete="current-password" required><br>

            <button type="submit">Login</button>
        </form>
    </div>

    <footer>
        <p>© 2024 by Khushleen Kaur. No rights reserved.</p>
    </footer>
</body>
</html>
