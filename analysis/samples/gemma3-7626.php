

<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate Input (Crucial for security - more details below)
  $product_name = $_POST["product_name"];
  $quantity = (int)$_POST["quantity"]; // Cast to integer to ensure valid quantity
  $price = (float)$_POST["price"]; // Cast to float to handle decimal values

  //  Basic Validation - Enhance this significantly in a real application
  if (empty($product_name) || $quantity <= 0 || $price <= 0) {
    $error_message = "Please fill in all fields and enter valid values.";
  } else {
    // Process the Order (Simulated)
    $order_id = 1; // Simple order ID, use a real database for actual IDs
    $total_amount = $quantity * $price;

    //  Simulate adding the order to a database (Replace with your database interaction)
    $sql = "INSERT INTO orders (product_name, quantity, price, order_id) VALUES ('$product_name', $quantity, $price, $order_id)";

    //  In a real application, you'd use a database connection (mysqli, PDO, etc.)
    //  For example:
    //  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    //  if ($conn->connect_error) {
    //    die("Connection failed: " . $conn->connect_error);
    //  }
    //  $conn->query($sql);
    //  $conn->close();


    $order_confirmation = "Order placed successfully! Product: $product_name, Quantity: $quantity, Total: $total_amount";

  }
}
?>
