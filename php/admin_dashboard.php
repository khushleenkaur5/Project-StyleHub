<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // If not logged in, redirect to the login page
    header('Location: admin_login.php');
    exit;
}

// Corrected syntax for including connect.php
require('connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/index.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        function confirmLogout(event) {
            event.preventDefault();
            const confirmAction = confirm("Are you sure you want to logout?");
            if (confirmAction) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Welcome to the Admin Dashboard</h1>
        <!-- Dropdown menu -->
        <div class="menu-container">
            <i class="fa-solid fa-bars menu-icon"></i>
            <div class="dropdown-menu">
                <a href="#" onclick="confirmLogout(event)">Logout</a>
            </div>
        </div>
    </header>

    <div class="dashboard-content">
        <div class="add-product">
            <a href="insert_product.php"><i class="fas fa-plus"></i>Add New Product</a>
    </div>
        <h2>Product List</h2>

        <?php
        // Fetch all products
        try {
            $stmt = $pdo->query("SELECT product_id, name, description, price, stock_quantity, image_url FROM products");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p>Error fetching products: " . htmlspecialchars($e->getMessage()) . "</p>";
            exit;
        }

        if ($products):
        ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock Quantity</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['product_id']) ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['description']) ?></td>
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['stock_quantity']) ?></td>
                            <td>
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $product['product_id'] ?>">Edit</a> |
                                <a href="delete_product.php?id=<?= $product['product_id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>© 2024 by Khushleen Kaur. No rights reserved.</p>
    </footer>
</body>
</html>
