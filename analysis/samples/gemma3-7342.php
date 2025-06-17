    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (very basic example - enhance this!)
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
  $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

  // Sanitize and escape the data for security
  $name = htmlspecialchars($name);
  $email = htmlspecialchars($email);
  $quantity = (int)$quantity; // Convert to integer
  $product_id = (int)$product_id;

  // Check if the product ID is valid
  if (!$product_id || $product_id <= 0) {
    $error_message = "Invalid product ID.";
  } else {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO orders (name, email, quantity, product_id) VALUES ('$name', '$email', '$quantity', '$product_id')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
      $success_message = "Order placed successfully! Thank you, " . $name . "!";
    } else {
      $error_message = "Error placing order: " . $conn->error;
    }

    // Close the connection
    $conn->close();
  }
}
?>
