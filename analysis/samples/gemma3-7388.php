    </ul>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (important for security - see notes below)
    $product_name = trim($_POST["product_name"]);
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]); // Using floatval to allow decimal values

    // Sanitize input (basic example - consider more robust sanitization)
    $product_name = htmlspecialchars($product_name);  // Prevent XSS
    $quantity = intval($quantity);  // Ensure quantity is an integer
    $price = floatval($price);

    // Error checking
    if (empty($product_name) || $quantity <= 0 || $price <= 0) {
        $error_message = "Please fill in all fields and ensure quantity and price are positive numbers.";
    } else {
        // Data is valid - proceed with purchase
        $total_amount = $quantity * $price;

        //  You would typically process the payment here.
        //  This is a placeholder - integrate with a payment gateway (Stripe, PayPal, etc.)
        //  In a real application, you'd handle the payment securely.
        echo "<h2>Purchase Confirmation</h2>";
        echo "<p>Product: " . $product_name . "</p>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price per item: $" . $price . "</p>";
        echo "<p>Total Amount: $" . $total_amount . "</p>";
        echo "<p>Thank you for your purchase!</p>";

        //  Optionally store the order information in a database:
        //  (This part needs more detailed implementation based on your database structure)
        //  You'd likely use a query like:
        //  INSERT INTO orders (product_name, quantity, price, total_amount, order_date)
        //  VALUES ('$product_name', $quantity, $price, $total_amount, NOW());
    }
}
?>
