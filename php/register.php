<?php
require 'connect.php';

$message = ""; // Initialize a variable to store the message
$success = false; // A flag to track successful registration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize POST data
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $_POST['address'] ?? '';
    $role = $_POST['role'] ?? 'customer';

    // Ensure all required fields are filled
    if (empty($first_name) || empty($last_name) || empty($email) || empty($address)) {
        $message = "Please fill all required fields.";
    } else {
        // SQL query to insert the data into the users table
        $sql = "INSERT INTO users (first_name, last_name, email, password, address, role) 
                VALUES (:first_name, :last_name, :email, :password, :address, :role)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
                'address' => $address,
                'role' => $role
            ]);
            $success = true; // Set success flag to true
            $message = "Registration successful!";
        } catch (PDOException $e) {
            $message = "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../css/index.css">
    <style>
    /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f3f4f6;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Form Container */
form {
    width: 400px;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e1e4e8;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

/* Form Inputs */
label {
    font-weight: bold;
    color: #555;
    display: block;
    margin-bottom: 8px;
}

input, select, button {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

input:focus, select:focus, button:focus {
    border-color: #007BFF;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

/* Button Styles */
button {
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

/* Message Box */
.message {
    text-align: center;
    font-size: 16px;
    color: green;
    margin-top: 10px;
}

.message.error {
    color: red;
}

/* Responsive Design */
@media (max-width: 500px) {
    form {
        width: 90%;
    }
}
</style>
    <script>
        // Function to show an alert and redirect
        function showLoginPrompt() {
            alert("Registration successful! Please login to continue.");
            window.location.href = "register.php"; 
        }
    </script>
</head>
<body>
    <form action="register.php" method="post">
        <h2>Register</h2>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="customer" selected>Customer</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>

    <!-- Display the message below the form -->
    <div class="message">
        <?= htmlspecialchars($message) ?>
    </div>

    <?php if ($success): ?>
        <script>
            // Trigger the prompt for successful registration
            showLoginPrompt();
        </script>
    <?php endif; ?>
</body>
</html>
