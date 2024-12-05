<?php
session_start();
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);

    // Fetch product details from the database
    $stmt = $pdo->prepare("SELECT product_id, name, price, stock_quantity FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $quantity > 0 && $quantity <= $product['stock_quantity']) {
        // Add product to the cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ];
        }
        header('Location: cart.php'); 
        exit();
    } else {
        echo "Invalid product or quantity.";
    }
}
?>
