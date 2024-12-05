<?php
/*******w*******
 * Name: Khushleen Kaur
 * Date: Nov 22, 2024
 * Description: This file provides options to view full post, edit, or delete posts, and add new posts if authenticated.
 */
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('connect.php');
include('navbar.php');

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']); // True if user_id is set in session
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Check if user is an admin

// Default search query and sorting order
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'product_id DESC'; // Default sorting by product ID

// Build the SQL query with the search and sort functionality
$query = "SELECT product_id, name, description, price, stock_quantity, image_url FROM products WHERE name LIKE :search OR description LIKE :search ORDER BY $sort";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute([':search' => '%' . $search . '%']);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StyleHub</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/search.css">
    <script src="../search.js" defer></script>
    <style>
        /* Styling the header search bar */
        .header-search {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .search-container {
            margin: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .search-container input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            border: 2px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .search-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        /* Styling the sort dropdown */
        .sort-form {
            margin-left: 20px;
        }

        .sort-form select {
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<header>
    <h1 class="main-title"><a href="index.php">StyleHub - Products</a></h1>

    <!-- Admin Login or User Information Display -->
    <div class="admin-logo">
        <?php if ($isLoggedIn): ?>
            <a href="admin_logo.php"><img src="../logo/user.png" alt="Admin Logo"></a>
            <p>Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?>!</p>
            <?php if ($isAdmin): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="admin_login.php"><img src="../logo/user.png" alt="Admin Logo"></a>
        <?php endif; ?>
    </div>

    <!-- Search Bar in the Header -->
    <div class="header-search">
        <form action="index.php" method="get" class="search-container">
            <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <form action="index.php" method="get" class="sort-form">
            <select name="sort" onchange="this.form.submit()">
                <option value="product_id DESC" <?= $sort === 'product_id DESC' ? 'selected' : '' ?>>Sort by Latest</option>
                <option value="price ASC" <?= $sort === 'price ASC' ? 'selected' : '' ?>>Sort by Price (Low to High)</option>
                <option value="price DESC" <?= $sort === 'price DESC' ? 'selected' : '' ?>>Sort by Price (High to Low)</option>
                <option value="name ASC" <?= $sort === 'name ASC' ? 'selected' : '' ?>>Sort by Name (A-Z)</option>
                <option value="name DESC" <?= $sort === 'name DESC' ? 'selected' : '' ?>>Sort by Name (Z-A)</option>
            </select>
        </form>
    </div>
</header>

<div class="container">
    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                <h2><a href="product.php?id=<?= $product['product_id'] ?>"><?= htmlspecialchars($product['name']) ?></a></h2>
                <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                <p><strong>Stock:</strong> <?= htmlspecialchars($product['stock_quantity']) ?></p>
                <p>
                    <?= strlen($product['description']) > 100 ? htmlspecialchars(substr($product['description'], 0, 100)) . '...' : htmlspecialchars($product['description']); ?>
                    <?php if (strlen($product['description']) > 100): ?>
                        <a href="product.php?id=<?= $product['product_id'] ?>">Read More</a>
                    <?php endif; ?>
                </p>
                 <!-- Add to Cart Form -->
        <form action="add_to_cart.php" method="post" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
            <label for="quantity-<?= $product['product_id'] ?>">Quantity:</label>
            <input type="number" id="quantity-<?= $product['product_id'] ?>" name="quantity" min="1" max="<?= $product['stock_quantity'] ?>" value="1">
            <button type="submit">Add to Cart</button>
        </form>
                <!-- Only show Edit/Delete buttons if the user is an admin -->
                <?php if ($isAdmin): ?>
                    <div class="admin-actions">
                        <a href="edit_product.php?id=<?= $product['product_id'] ?>">Edit</a> |
                        <a href="delete_product.php?id=<?= $product['product_id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<footer>
    <p>© 2024 by Khushleen Kaur. No rights reserved.</p>
</footer>
</body>
</html>
