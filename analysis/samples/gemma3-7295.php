
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Product</title>
</head>
<body>

  <h1>Purchase Product</h1>

  <form method="post" action="">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <br><br>

    <label for="user_id">User ID:</label>
    <input type="number" id="user_id" name="user_id" required>
    <br><br>

    <button type="submit">Purchase</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPassword = "your_password";
$dbName = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs (This is a basic example - enhance with more robust validation)
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);

    // Perform input validation
    if (empty($name) || empty($email) || $quantity <= 0 || $price <= 0) {
        $error = "Please fill in all fields and ensure quantities and prices are positive values.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Process the purchase
        $order_total = $quantity * $price;

        // Generate an order ID (simple example - improve with a more sophisticated system)
        $order_id = uniqid();

        //  Simulate saving to a database (Replace with your database interaction logic)
        $sql = "INSERT INTO orders (order_id, name, email, quantity, price, order_total)
                VALUES ('$order_id', '$name', '$email', $quantity, $price, $order_total)";

        // Execute the SQL query
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            $message = "Order placed successfully! Your order ID is: $order_id";
        } else {
            $message = "Error placing order.  Query: " . $sql . "<br>Error: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
    }
}
?>
