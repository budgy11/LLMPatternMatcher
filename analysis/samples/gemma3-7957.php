

<?php

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize inputs
  $product_name = trim($_POST["product_name"]);
  $product_price = floatval($_POST["product_price"]);
  $quantity = intval($_POST["quantity"]);

  //  Input validation (add more as needed!)
  if (empty($product_name) || $product_price <= 0 || $quantity <= 0) {
    $error_message = "Please enter valid product name, price, and quantity.";
  } else {
    // Prepare the SQL statement
    $sql = "INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sii", $product_name, $product_price, $quantity);

    // Execute the statement
    if ($stmt->execute()) {
      $success_message = "Product added successfully!";
    } else {
      $error_message = "Error adding product: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }

}

// Establish database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
