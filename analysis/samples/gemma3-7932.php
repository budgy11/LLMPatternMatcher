    <br><br>

    <label for="quantities">Quantities:</label>
    <input type="text" id="quantities" name="quantities"  placeholder="Enter quantities (comma-separated)">
    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate inputs (essential for security!)
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
  $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT); // Assuming you have a product_id

  // Check if required fields are filled
  if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
    $error = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif ($quantity <= 0) {
    $error = "Quantity must be a positive integer.";
  } else {
    // --- Process the purchase ---

    // 1.  Store the order information (e.g., in a database)
    //    This is a basic example - you'd likely want a more robust order system.
    $order_id = generate_order_id(); // Function to generate a unique order ID
    $sql = "INSERT INTO orders (order_id, name, email, quantity, product_id)
            VALUES ('$order_id', '$name', '$email', '$quantity', '$product_id')";

    // Execute the query
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if ($conn->query($sql) === TRUE) {
      $success = "Order placed successfully! Order ID: $order_id";
    } else {
      $error = "Error placing order: " . $conn->error;
    }

    $conn->close();

  }
}
?>
