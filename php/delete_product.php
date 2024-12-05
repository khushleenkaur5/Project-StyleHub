<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

require('connect.php');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        header('Location: admin_dashboard.php');
        exit;
    } catch (PDOException $e) {
        echo "Error deleting product: " . htmlspecialchars($e->getMessage());
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
