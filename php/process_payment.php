<?php
session_start();
require_once 'connect.php';

// Ensure form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = htmlspecialchars($_POST['card_number']);
    $expiryDate = htmlspecialchars($_POST['expiry_date']);
    $cvv = htmlspecialchars($_POST['cvv']);
    $cardHolder = htmlspecialchars($_POST['card_holder']);
    $checkoutId = intval($_POST['checkout_id']);

    try {
        // Example: Save payment details in the database (hashed/sanitized as needed)
        $stmt = $db->prepare(
            "INSERT INTO payment (checkout_id, card_number, expiry_date, cvv, card_holder, created_at) 
             VALUES (:checkout_id, :card_number, :expiry_date, :cvv, :card_holder, NOW())"
        );
        $stmt->execute([
            ':checkout_id' => $checkoutId,
            ':card_number' => $cardNumber, // Consider hashing for real systems
            ':expiry_date' => $expiryDate,
            ':cvv' => $cvv, // NEVER store CVV in real systems
            ':card_holder' => $cardHolder
        ]);

        // Payment successful, clear checkout session
        unset($_SESSION['checkout']);
        header('Location: payment_success.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: payment.php');
    exit;
}
?>
