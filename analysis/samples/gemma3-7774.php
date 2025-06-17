

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs (VERY IMPORTANT!)
    $product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : 0;
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

    // Input validation - Ensure product_id and quantity are positive integers
    if ($product_id <= 0 || $quantity <= 0) {
        $error_message = "Invalid product ID or quantity. Please enter positive values.";
    } else {
        // Get product details from the database
        $sql = "SELECT id, name, price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $product_name = $product["name"];
            $product_price = $product["price"];

            // Calculate the total price
            $total_price = $product_price * $quantity;

            // Display the purchase details
            echo "<p>You purchased: " . $quantity . " " . $product_name . " for $" . $total_price . "</p>";

            // (Optional) Add purchase to a database table
            // You would typically insert data into a 'purchases' table here.
            // For demonstration purposes, we'll just print a success message.

            echo "<p>Thank you for your purchase!</p>";


        } else {
            $error_message = "Product not found.";
        }
    }
}
?>
