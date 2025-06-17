
</body>
</html>


<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "e_commerce";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $quantity = (int) $_POST["quantity"]; // Convert to integer
  $price = (float) $_POST["price"]; // Convert to float

  // Validation - Add more robust validation as needed (e.g., email format validation)
  if (empty($name) || empty($email) || $quantity <= 0 || $price <= 0) {
    $error = "Please fill in all fields and ensure quantity and price are positive values.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // Process the order (Simulate Order Processing)
    $order_total = $quantity * $price;

    //  You would typically insert this into a database here
    //  For this example, we'll just display the order details

    echo "<p>Order Summary:</p>";
    echo "<p>Name: " . htmlspecialchars($name) . "</p>"; // Escape for security
    echo "<p>Email: " . htmlspecialchars($email) . "</p>";
    echo "<p>Quantity: " . $quantity . "</p>";
    echo "<p>Price per item: $" . htmlspecialchars($price) . "</p>";
    echo "<p>Total: $" . $order_total . "</p>";
    echo "<p>Thank you for your order!</p>";

    // You would typically log this order, send an email confirmation, etc.
  }
}
?>
