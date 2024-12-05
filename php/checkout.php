<?php
session_start();
require_once 'connect.php';
include('navbar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $postalCode = htmlspecialchars($_POST['postal_code']);
    $total = calculateTotal($_SESSION['cart']);

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO checkout (first_name, last_name, email, address, city, postal_code, total, created_at) 
             VALUES (:first_name, :last_name, :email, :address, :city, :postal_code, :total, NOW())"
        );
        $stmt->execute([
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $email,
            ':address' => $address,
            ':city' => $city,
            ':postal_code' => $postalCode,
            ':total' => $total
        ]);

        unset($_SESSION['cart']);
        $_SESSION['success_message'] = "Congratulations! Your order has been placed successfully.";
        header('Location: checkout.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

$successMessage = '';
if (!empty($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
<header>
    <h1>Checkout</h1>
</header>
<?php if ($successMessage): ?>
    <div class="success-message">
        <?= htmlspecialchars($successMessage) ?>
    </div>
<?php endif; ?>
<div class="checkout-form">
    <form action="checkout.php" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>
        <label for="postal_code">Postal Code:</label>
        <input type="text" id="postal_code" name="postal_code" required>
        <button type="submit">Place Order</button>
    </form>
</div>
<footer>© 2024 Khushleen Kaur</footer>
</body>
</html>
