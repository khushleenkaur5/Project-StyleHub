<?php
/*w*******
 * Name: Khushleen Kaur
 * Date: November 24, 2024
 * Description: Establishes a connection to the database using PDO. 
 ****************/
// Enable error reporting for debugging

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require('connect.php');
include('navbar.php');

// Get the product_id from the URL
$product_id = $_GET['id'] ?? null;
$captcha_error = $comment_error = $success_message = null;

// Generate CAPTCHA if needed
if (!isset($_SESSION['captcha_text']) || empty($_SESSION['captcha_text'])) {
    $captcha_code = (string)rand(1000, 9999);  // Simple numeric CAPTCHA
    $_SESSION['captcha_text'] = $captcha_code;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CAPTCHA
    if (!isset($_POST['captcha']) || trim($_POST['captcha']) !== $_SESSION['captcha_text']) {
        $captcha_error = "Incorrect CAPTCHA. Please try again.";
        // Regenerate CAPTCHA on failure
        $_SESSION['captcha_text'] = (string)rand(1000, 9999);
    } else {
        // Process the comment
        $name = htmlspecialchars($_POST['name']);
        $comment = htmlspecialchars($_POST['comment']);
        
        if (empty($name) || empty($comment)) {
            $comment_error = "Both name and comment are required.";
        } else {
            try {
                // Insert the comment into the database
                $stmt = $pdo->prepare("INSERT INTO comments (product_id, name, comment, created_at) VALUES (:product_id, :name, :comment, NOW())");
                $stmt->execute([
                    'product_id' => $product_id,
                    'name' => $name,
                    'comment' => $comment,
                ]);
                $success_message = "Comment submitted successfully!";
                // Regenerate CAPTCHA after successful submission
                $_SESSION['captcha_text'] = (string)rand(1000, 9999);
            } catch (PDOException $e) {
                // Output error if insertion fails
                echo "Error inserting comment: " . $e->getMessage();
            }
        }
    }
}

// Retrieve product details
if ($product_id) {
    $stmt = $pdo->prepare("SELECT name FROM products WHERE product_id = :id");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

// Fetch comments for this product in reverse chronological order
$comments = $pdo->prepare("SELECT name, comment, created_at FROM comments WHERE product_id = :id ORDER BY created_at DESC");
$comments->execute(['id' => $product_id]);
$comment_list = $comments->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comments for <?= htmlspecialchars($product['name']) ?> | StyleHub</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
<header><h1 class="main-title"><a href="index.php">StyleHub</a></h1></header>

<!-- Product Name -->
<h2>Comments for <?= htmlspecialchars($product['name']) ?></h2>

<!-- Comment Form -->
<div class="comment-section">
    <h3>Leave a Comment</h3>

    <?php if ($success_message): ?>
        <p class="success"><?= $success_message ?></p>
    <?php endif; ?>

    <?php if ($captcha_error): ?>
        <p class="error"><?= $captcha_error ?></p>
    <?php endif; ?>

    <?php if ($comment_error): ?>
        <p class="error"><?= $comment_error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="comment">Comment:</label>
        <textarea name="comment" required></textarea>

        <div class="captcha">
            <label for="captcha">Enter CAPTCHA: <?= $_SESSION['captcha_text'] ?></label>
            <input type="text" name="captcha" required>
        </div>

        <button type="submit">Submit Comment</button>
    </form>
</div>

<!-- Display Comments -->
<div class="comments-list">
    <h3>Comments</h3>
    <?php if (empty($comment_list)): ?>
        <p>No comments yet.</p>
    <?php else: ?>
        <?php foreach ($comment_list as $comment): ?>
            <div class="comment">
                <strong><?= htmlspecialchars($comment['name']) ?></strong>
                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                <p class="timestamp"><?= $comment['created_at'] ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Back to Product Page -->
<a href="product.php?id=<?= $product_id ?>" class="button">Back to Product</a>

<footer>
    <p>© 2024 by Khushleen Kaur. No rights reserved.</p>
</footer>
</body>
</html>
