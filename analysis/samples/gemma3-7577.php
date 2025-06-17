
<br>
<a href="cart.php">View Cart</a> | <a href="checkout.php">Checkout</a>


</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize input
  $name = $_POST["name"];
  $email = $_POST["email"];
  $quantity = $_POST["quantity"];
  $product_id = $_POST["product_id"]; // Assuming you have a product_id for each item

  // Basic input validation (customize as needed)
  if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif (intval($quantity) <= 0) {
    $error = "Quantity must be a positive integer.";
  }

  // If no errors, proceed with the purchase
  if (empty($error)) {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO purchases (name, email, product_id, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("sii", $name, $product_id, $quantity);
      $stmt->execute();
      $stmt->close();
    } else {
      echo "Error preparing statement.";
      $stmt->close();
      exit;
    }

    // Success message
    $success_message = "Purchase successful! Thank you for your order.";

    // Close the database connection
    $conn->close();
  }
}
?>
