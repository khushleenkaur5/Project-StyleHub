<?php

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $image_url = null;

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
            $image_url = $targetFile; // Save file path to the variable
        } else {
            echo "<p>Error: Unable to move the uploaded file. Check directory permissions.</p>";
        }
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors
        echo "<p>Error uploading image. Error Code: " . $_FILES['image']['error'] . "</p>";
    }

    // Insert data into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock_quantity, image_url) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock_quantity, $image_url]);

        // Show a success prompt and redirect
        echo "<script>
                alert('Product added successfully!');
                window.location.href = 'admin_dashboard.php'; // Change this to your admin dashboard URL
              </script>";
        exit;
    } catch (PDOException $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
    <link rel="stylesheet" href="../css/index.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="file"],
        .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" required>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*">

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
