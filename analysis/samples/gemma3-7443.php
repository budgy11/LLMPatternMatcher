
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Purchase Form</h1>

<form method="post" action="">
    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="cart">Cart (Product ID:Quantity):</label>
    <input type="text" id="cart" name="cart" value="1:2,2:1" required><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual values)
$db_host = "localhost";
$db_name = "ecommerce_db";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize input
    $product_id = isset($_POST["product_id"]) ? $_POST["product_id"] : null;
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0; // Ensure quantity is an integer
    $customer_name = isset($_POST["customer_name"]) ? htmlspecialchars($_POST["customer_name"]) : "";
    $customer_email = isset($_POST["customer_email"]) ? htmlspecialchars($_POST["customer_email"]) : "";

    // Input validation (Add more robust validation as needed)
    if (!is_numeric($product_id) || $product_id <= 0) {
        $error_message = "Invalid product ID.";
    } elseif ($quantity <= 0) {
        $error_message = "Invalid quantity.";
    } else {
        //  Database query (Replace with your actual product table structure)
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email)
                VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Order placed successfully!";
        } else {
            $error_message = "Error placing order: " . $conn->error;
        }

        $conn->close();
    }
}
?>
