
  <hr>

  <!-- Purchase Form -->
  <h2>Place Order</h2>
  <form method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" id="payment_method" name="payment_method" required>
      <option value="credit_card">Credit Card</option>
      <option value="paypal">PayPal</option>
    </select><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Form Handling (Example using GET, can be adapted to POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Form Data (Essential for Security!)
    $product_name = $_POST["product_name"];
    $quantity = $_POST["quantity"];
    $customer_name = $_POST["customer_name"];

    // Basic Validation (Expand this for more robust validation)
    if (empty($product_name) || empty($quantity) || empty($customer_name)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($quantity) || $quantity <= 0) {
        $error = "Quantity must be a positive number.";
    } else {
        // Database Interaction (Insert Data)
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement (Use prepared statements for security!)
        $sql = "INSERT INTO orders (product_name, quantity, customer_name) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $product_name, $quantity);  // "ss" for string, string
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }

        // Success Message
        if ($stmt) {
            $success_message = "Order placed successfully! Thank you, " . $customer_name . ".";
        } else {
            $success_message = "";
        }

    }
}
?>
