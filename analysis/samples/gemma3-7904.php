
</body>
</html>


<?php

// Database connection (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data
    $product_name = trim($_POST["product_name"]);
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);

    // Sanitize input (Important for security!)
    $product_name = htmlspecialchars($product_name);
    $quantity = intval($quantity);
    $price = floatval($price);

    // Check if data is valid
    if (empty($product_name) || $quantity <= 0 || $price <= 0) {
        $error = "Please fill in all fields and ensure quantity and price are positive values.";
    } else {
        // Calculate the total price
        $total_price = $quantity * $price;

        // Store the order information (Replace with your database logic)
        // This is a simple example, you'll likely want to use a database
        $order_data = array(
            "product_name" => $product_name,
            "quantity" => $quantity,
            "price" => $price,
            "total_price" => $total_price,
            "order_date" => date("Y-m-d H:i:s")
        );

        // Store the order data (Example: Storing in a session - not ideal for production)
        session_start();
        $_SESSION["shopping_cart"][] = $order_data;

        // Redirect to a success page
        header("Location: success.php");
        exit(); // Important to stop further script execution
    }
}
?>
