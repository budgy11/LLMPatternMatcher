
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

<h1>Add to Cart Example</h1>

<form method="post" action="">

    <label for="product1">Product 1:</label>
    <input type="number" id="product1" name="cart_items[0]" value="1" min="1"> <br><br>

    <label for="product2">Product 2:</label>
    <input type="number" id="product2" name="cart_items[1]" value="1" min="1"> <br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data to prevent SQL injection
    $product_name = $_POST["product_name"];
    $quantity = (int)$_POST["quantity"]; // Cast to integer
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];

    // Validate input (add more robust validation as needed)
    if (empty($product_name) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($quantity) || $quantity <= 0) {
        $error = "Quantity must be a positive number.";
    } else {
        // Prepare SQL query to insert data
        $sql = "INSERT INTO orders (product_name, quantity, customer_name, customer_email)
                VALUES ('$product_name', $quantity, '$customer_name', '$customer_email')";

        if ($conn->query($sql)) {
            $success = "Order placed successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

?>
