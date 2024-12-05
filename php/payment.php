<?php
session_start();

// Ensure checkout details are present
if (!isset($_SESSION['checkout'])) {
    header('Location: cart.php');
    exit;
}

// Retrieve checkout details
$checkoutDetails = $_SESSION['checkout'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" href="../css/payment.css">
</head>
<body>
<header>
    <h1>Payment</h1>
</header>
<div class="payment-container">
    <h2>Order Summary</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($checkoutDetails['first_name'] . ' ' . $checkoutDetails['last_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($checkoutDetails['email']) ?></p>
    <p><strong>Total Amount:</strong> $<?= number_format($checkoutDetails['total'], 2) ?></p>

    <form action="process_payment.php" method="post">
        <label for="card_number">Card Number:</label>
        <input type="text" id="card_number" name="card_number" required placeholder="1234 5678 9012 3456">

        <label for="expiry_date">Expiry Date:</label>
        <input type="text" id="expiry_date" name="expiry_date" required placeholder="MM/YY">

        <label for="cvv">CVV:</label>
        <input type="password" id="cvv" name="cvv" required placeholder="123">

        <label for="card_holder">Card Holder Name:</label>
        <input type="text" id="card_holder" name="card_holder" required placeholder="John Doe">

        <input type="hidden" name="checkout_id" value="<?= htmlspecialchars($checkoutDetails['id']) ?>">
        <button type="submit">Make Payment</button>
    </form>
</div>
<footer>
    <p>© 2024 by Khushleen Kaur. All rights reserved.</p>
</footer>
</body>
</html>
