<?php
session_start();
require_once 'connect.php';
include('navbar.php');
$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Please enter a valid email address.";
    } else {
        try {
            // Save the message in the database (optional)
            $stmt = $pdo->prepare(
                "INSERT INTO contact_messages (name, email, message, created_at) 
                 VALUES (:name, :email, :message, NOW())"
            );
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message
            ]);

            // Set success message and redirect to clear form
            $_SESSION['success_message'] = "Thank you, $name! Your message has been received.";
            header('Location: contact.php');
            exit;
        } catch (PDOException $e) {
            $errorMessage = "There was an error saving your message. Please try again.";
        }
    }
}

// Display success message if available
if (!empty($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
<header>
    <h1>Contact Us</h1>
</header>

<div class="contact-form">
    <!-- Success Message -->
    <?php if ($successMessage): ?>
        <div class="success-message">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if ($errorMessage): ?>
        <div class="error-message">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <form action="contact.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required><?= isset($message) ? htmlspecialchars($message) : '' ?></textarea>

        <button type="submit">Send Message</button>
    </form>
</div>

<footer>© 2024 Khushleen Kaur</footer>
</body>
</html>
