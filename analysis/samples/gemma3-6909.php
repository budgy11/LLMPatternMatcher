

<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (Important for security!)
    $product_name = trim($_POST["product_name"]);
    $quantity = intval($_POST["quantity"]);
    $total_price = floatval($_POST["total_price"]); // Consider using a database query for accurate pricing.

    // Sanitize input (Very important to prevent security vulnerabilities like XSS)
    $product_name = htmlspecialchars($product_name);
    $quantity = intval($quantity);

    // Basic validation (Add more thorough validation as needed)
    if (empty($product_name) || $quantity <= 0 || $total_price <= 0) {
        $error_message = "Please fill in all fields correctly and ensure quantities and prices are positive.";
    } else {
        // Store the order data (This is a simplified example - for production,
        // store in a database)
        $order_data = [
            "product_name" => $product_name,
            "quantity" => $quantity,
            "total_price" => $total_price,
            "order_date" => date("Y-m-d H:i:s")
        ];

        // Display order confirmation
        echo "<div class='order-confirmation'>";
        echo "<h2>Order Confirmation</h2>";
        echo "<p><strong>Product:</strong> " . $product_name . "</p>";
        echo "<p><strong>Quantity:</strong> " . $quantity . "</p>";
        echo "<p><strong>Total Price:</strong> $" . number_format($total_price, 2) . "</p>";  // Format as currency
        echo "<p><strong>Order Date:</strong> " . $order_data["order_date"] . "</p>";
        echo "</div>";

        // (Optional) Log the order to a file or database
        // file_put_contents("order_log.txt", $order_data . PHP_EOL, FILE_APPEND);
    }
}
?>
