
</body>
</html>


<?php

// Database connection details
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'your_user';
$db_password = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

    // Check if all fields are valid
    if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
        $error_message = "Please fill in all fields.";
    } elseif (empty($quantity)) {
        $error_message = "Quantity must be a valid number.";
    } elseif (!is_numeric($quantity)) {
        $error_message = "Quantity must be a number.";
    } elseif (!is_int($product_id)) {
        $error_message = "Invalid Product ID.";
    }

    // If no errors, proceed with the purchase
    if (empty($error_message)) {

        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement
        $sql = "INSERT INTO orders (name, email, quantity, product_id) VALUES (?, ?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ssii", $name, $email, $quantity, $product_id);

        // Execute statement
        if ($stmt->execute()) {
            echo "Order placed successfully! Thank you for your purchase.";
        } else {
            echo "Order placement failed: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Display error message
        echo "<p style='color: red;'>Error: " . $error_message . "</p>";
    }
}
?>
