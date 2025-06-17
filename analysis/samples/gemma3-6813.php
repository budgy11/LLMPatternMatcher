

<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize input
  $product_name = trim($_POST["product_name"]);
  $product_price = floatval($_POST["product_price"]);
  $quantity = intval($_POST["quantity"]);

  //  Basic validation - Add more robust validation as needed
  if (empty($product_name) || $product_price <= 0 || $quantity <= 0) {
    $error_message = "Please fill in all fields and ensure prices and quantities are positive.";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO products (name, price, quantity) VALUES ('$product_name', $product_price, $quantity)";

    // Execute the query
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if ($conn->query($sql) === TRUE) {
      $success_message = "Product added successfully!";
    } else {
      $error_message = "Error adding product: " . $conn->error;
    }

    $conn->close();
  }
}
?>
