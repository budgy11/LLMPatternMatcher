  <p>Price: $<?php echo number_format($product_price, 2); ?></p>

  <form method="post">
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required>
    <button type="submit">Purchase</button>
  </form>

  <p><a href="index.php">Back to Home</a></p>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// Replace these placeholders with your actual connection details

// Database credentials
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form handling (for POST data)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (essential for security!)
    $product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : 0;
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;
    $customer_name = isset($_POST["customer_name"]) ? htmlspecialchars($_POST["customer_name"]) : "";
    $customer_email = isset($_POST["customer_email"]) ? htmlspecialchars($_POST["customer_email"]) : "";

    // Check if product ID is valid
    if ($product_id <= 0) {
        $error = "Invalid product ID.";
    } elseif ($quantity <= 0) {
        $error = "Invalid quantity.";
    } else {

        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email)
                VALUES (?, ?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters (important for security - prevents SQL injection)
        $stmt->bind_param("isss", $product_id, $quantity, $customer_name, $customer_email);

        // Execute the query
        if ($stmt->execute()) {
            $success = "Order placed successfully!";
        } else {
            $error = "Order placement failed: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
