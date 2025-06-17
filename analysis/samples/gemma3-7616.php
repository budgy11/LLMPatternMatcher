

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize input
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
  $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);

  // Check for required fields
  if (empty($name) || empty($quantity) || empty($price)) {
    $error = "Please fill in all fields.";
  } else if ($quantity <= 0 || $price <= 0) {
    $error = "Quantity and Price must be greater than zero.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO products (name, quantity, price) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sii", $name, $quantity, $price);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Product added successfully!";
    } else {
      $error = "Error adding product: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
