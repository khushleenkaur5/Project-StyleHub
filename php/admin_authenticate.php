<?php
/******w**********
 * Name: Khushleen Kaur
 * Date: Nov 22, 2024
 * Description: Manages user authentication and logout for the StyleHub project.
 *****************/

session_start();

// Define admin credentials
define('ADMIN_EMAIL', 'khushi@13gmail.com'); // Correct email format
define('ADMIN_PASSWORD', 'cutebaby'); // Defined password for admin login

// Check if the user requested to log out
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Destroy the session and redirect to the login page
    session_destroy();
    header('Location: admin_login.php'); // Redirect to login page
    exit;
}

// Check if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data (email and password)
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate credentials
    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        // If credentials match, set session variable and redirect to dashboard
        $_SESSION['user_logged_in'] = true;
        header('Location: admin_dashboard.php'); // Redirect to the admin dashboard
        exit;
    } else {
        // If credentials don't match, set an error message
        $_SESSION['login_error'] = 'Invalid email or password.';
        header('Location: admin_login.php'); // Redirect back to the login form
        exit;
    }
}

// If user is already logged in, redirect to the admin dashboard
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: admin_dashboard.php'); // Redirect to the admin dashboard
    exit;
}
?>


