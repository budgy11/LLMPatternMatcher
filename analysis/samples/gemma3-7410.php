

<?php
session_start();

// Database connection (replace with your actual database credentials)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $quantity = (int)($_POST["quantity"]);
    $product_id = (int)($_POST["product_id"]);

    // Basic validation (more robust validation is recommended)
    if (empty($name) || empty($email) || $quantity <= 0 || $product_id <= 0) {
        $error = "Please fill in all fields and ensure quantity and product ID are positive.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Process the purchase
        // 1.  Insert the order into the database
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO orders (name, email, product_id, quantity) VALUES ('$name', '$email', $product_id, $quantity)";

        if ($conn->query($sql) === TRUE) {
            $success = "Order placed successfully! Order ID: " . $conn->insert_id;
        } else {
            $error = "Error placing order: " . $conn->error;
        }

        $conn->close();
    }
}
?>
