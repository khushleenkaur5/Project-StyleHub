<?php
/*******w******** 
    Name: Khushleen Kaur  
    Date: November 22, 2024
    Description: This file retrieves product data based on the product ID passed in the URL.
****************/
include('navbar.php');
require('connect.php');
$product_id = $_GET['id'] ?? null;

if ($product_id) {
    $stmt = $pdo->prepare("SELECT name, description, price, stock_quantity, image_url FROM products WHERE product_id = :id");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> | StyleHub</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
<header><h1 class="main-title"><a href="index.php">StyleHub</a></h1></header>

<!-- Product Card Container -->
<div class="product-card-container">
    <div class="product-card">
        <h1 class="product-name"><?= htmlspecialchars($product['name']) ?></h1>
        <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
        <p><strong>Stock:</strong> <?= htmlspecialchars($product['stock_quantity']) ?> available</p>
        <div class="product-description">
            <strong>Description:</strong>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>
        
        <?php if (!empty($product['image_url'])): ?>
            <div class="product-image-container">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
            </div>
        <?php endif; ?>

        <a href="index.php" class="button home-button">Back to Products</a>

        <a href="product_comments.php?id=<?= $product_id ?>" class="button comments-button">View Comments</a>
    </div>
</div>

<footer>
    <p>© 2024 by Khushleen Kaur. No rights reserved.</p>
</footer>
</body>
</html>
