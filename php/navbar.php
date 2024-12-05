<!-- navbar.php -->
<?php
// Start the session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">

</head>
<body>
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="checkout.php">Checkout</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="register.php">Register</a></li>
        <?php
        if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
            // If user is logged in
            echo '<li><a href="logout.php">Logout</a></li>';
        } else {
            // If user is not logged in
            echo '<li><a href="login.php">Login</a></li>';
        }
        ?>
    </ul>
</nav>
</body>
</html>

