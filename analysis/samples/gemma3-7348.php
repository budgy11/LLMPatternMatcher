    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" min="1" required><br><br>

    <button type="submit">Place Order</button>
  </form>

  <p>Available Products: <a href="products.php">View Products</a></p>

</body>
</html>


<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $product_name = $_POST["product_name"];
  $quantity = $_POST["quantity"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];

  // Validate input (Important!  Prevent security vulnerabilities)
  if (empty($product_name) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
    $error_message = "Please fill in all fields.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error_message = "Quantity must be a positive number.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement (important for security - use prepared statements!)
    $sql = "INSERT INTO orders (product_name, quantity, customer_name, customer_email) VALUES (?, ?, ?, ?)";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind parameters
      $stmt->bind_param("sss", $product_name, $quantity, $customer_name, $customer_email);

      // Execute the statement
      if ($stmt->execute()) {
        $success_message = "Order placed successfully! Thank you, " . $customer_name . "!";
      } else {
        $error_message = "Order placement failed: " . $stmt->error;
      }
    } else {
      $error_message = "Error preparing statement.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}

?>
