

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
  $product_name = filter_input(INPUT_POST, "product_name", FILTER_SANITIZE_STRING);


  // Check if validation passed
  if (empty($name) || empty($email) || empty($quantity) || empty($product_name)) {
    $error_message = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif ($quantity <= 0) {
    $error_message = "Quantity must be a positive integer.";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO orders (name, email, quantity, product_name) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $db->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ssii", $name, $email, $quantity, $product_name);

    // Execute the statement
    if ($stmt->execute()) {
      $success_message = "Order placed successfully!";
    } else {
      $error_message = "Error placing order. " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}

// Database connection
$db = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check the connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

?>
