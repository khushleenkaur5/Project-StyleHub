<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

require('connect.php');

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description']; // The description with tags
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $image_url = null; // Initialize as null

    // Remove HTML tags (like <p>, <strong>, etc.) from the description
    $description = strip_tags($description);

    // Handle image upload
    if (isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;

        // Create directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image_url = $targetFile; // Save the new file path
        } else {
            echo "<script>
                alert('Error: Unable to upload the image. Please try again.');
                window.history.back();
            </script>";
            exit;
        }
    }

    try {
        // Update product details in the database
        if ($image_url) {
            // If a new image is uploaded, update all fields including the image_url
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, image_url = ? WHERE product_id = ?");
            $stmt->execute([$name, $description, $price, $stock_quantity, $image_url, $product_id]);
        } else {
            // If no new image is uploaded, update all fields except the image_url
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ? WHERE product_id = ?");
            $stmt->execute([$name, $description, $price, $stock_quantity, $product_id]);
        }

        // Show success prompt and redirect
        echo "<script>
            alert('Product updated successfully!');
            window.location.href = 'admin_dashboard.php';
        </script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>
            alert('Error updating product: " . htmlspecialchars($e->getMessage()) . "');
            window.history.back();
        </script>";
        exit;
    }
} else {
    $product_id = $_GET['id'];

    try {
        // Fetch product details
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo "<script>
                alert('Product not found.');
                window.location.href = 'admin_dashboard.php';
            </script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Error fetching product: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = 'admin_dashboard.php';
        </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/edit.css">

    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.0/classic/ckeditor.js"></script>

</head>
<body>
    <h1>Edit Product</h1>
    <form action="edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label for="stock_quantity">Stock Quantity:</label>
        <input type="number" id="stock_quantity" name="stock_quantity" value="<?= htmlspecialchars($product['stock_quantity']) ?>" required>

        <label for="current_image">Current Image:</label>
        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Current Image" style="max-width: 200px; max-height: 200px;">

        <label for="image">Upload New Image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit">Update Product</button>
         <button onclick="window.location.href='admin_dashboard.php'>Back to Dashboard</button>
    </form>

    <script>
        // Initialize CKEditor for the description field
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>
