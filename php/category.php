<?php
// Database connection
try {
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get category from query parameters
$category = $_GET['category'] ?? null;

// Fetch products based on the category
if ($category) {
    $stmt = $db->prepare("
        SELECT 
            p.product_id,
            p.name AS product_name,
            p.description,
            p.price,
            p.stock_quantity,
            p.image_url
        FROM 
            products p
        JOIN 
            product_categories pc ON p.product_id = pc.product_id
        JOIN 
            categories c ON pc.category_id = c.category_id
        WHERE 
            c.name = :category
        ORDER BY 
            p.product_id DESC
    ");
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->execute();
} else {
    // Fetch all products if no category is selected
    $stmt = $db->query("
        SELECT 
            p.product_id,
            p.name AS product_name,
            p.description,
            p.price,
            p.stock_quantity,
            p.image_url
        FROM 
            products p
        ORDER BY 
            p.product_id DESC
    ");
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="category-nav">
        <ul>
            <li><a href="index.php?category=Men">Men</a></li>
            <li><a href="index.php?category=Women">Women</a></li>
            <li><a href="index.php?category=Child">Child</a></li>
            <li><a href="index.php">All Products</a></li>
        </ul>
    </nav>

    <div class="container">
        <?php if (empty($products)): ?>
            <p>No products found for this category.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="product-image">
                    <h2><?= htmlspecialchars($product['product_name']) ?></h2>
                    <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                    <p><strong>Stock:</strong> <?= htmlspecialchars($product['stock_quantity']) ?></p>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
