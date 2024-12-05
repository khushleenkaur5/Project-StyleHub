<?php

/*w** 
    Name: Khushleen Kaur
    Date: 29-11-2024
    Description: User Input Validation Assignment
******/

session_start();
include('navbar.php');
// Initialize the cart if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to calculate total
function calculateTotal($cart)
{
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Remove product from the cart
if (isset($_POST['remove'])) {
    $productId = $_POST['product_id'];
    unset($_SESSION['cart'][$productId]);
}

// Update quantity
if (isset($_POST['update'])) {
    $productId = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
<header>
    <h1>Shopping Cart</h1>
</header>
<div class="cart-container">
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $productId => $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="number" name="quantity" value="<?= $product['quantity'] ?>" min="1">
                                <input type="hidden" name="product_id" value="<?= $productId ?>">
                                <button type="submit" name="update">Update</button>
                            </form>
                        </td>
                        <td>$<?= number_format($product['price'] * $product['quantity'], 2) ?></td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?= $productId ?>">
                                <button type="submit" name="remove">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>Total: $<?= number_format(calculateTotal($_SESSION['cart']), 2) ?></strong></p>
    
        <div class="checkout-button">
    <a href="checkout.php" class="btn">Proceed to Checkout</a>
</div>

        <?php endif; ?>
</div>
<footer>
    <p>© 2024 by Khushleen Kaur. No rights reserved.</p>
</footer>
</body>
</html>
