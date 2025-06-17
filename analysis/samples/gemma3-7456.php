        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required><br><br>

        <button type="submit">Purchase</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
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

// ---------------------  Purchase Functionality ---------------------

// 1. Add Purchase Form Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are filled
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    if (empty($product_id) || empty($quantity)) {
        $error = "Please fill in all fields.";
    } else {
        // Validate quantity (ensure it's a number)
        if (!is_numeric($quantity) || $quantity <= 0) {
            $error = "Invalid quantity. Please enter a positive number.";
        } else {
            // Sanitize input (Important for security - prevent SQL injection)
            $product_id = mysqli_real_escape_string($conn, $product_id);
            $quantity = mysqli_real_escape_string($conn, $quantity);

            // Perform the purchase (insert into a "purchases" table)
            $sql = "INSERT INTO purchases (product_id, quantity) VALUES ('$product_id', '$quantity')";

            if ($conn->query($sql) === TRUE) {
                $success = "Purchase added successfully!";
            } else {
                $error = "Error adding purchase: " . $conn->error;
            }
        }
    }
}


// 2. Display Purchase Form (For initial page load)
?>
