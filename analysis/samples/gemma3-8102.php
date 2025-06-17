

<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize the input data
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
  $product_id = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);


  // Check if required fields are filled
  if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
    $error_message = "Please fill in all fields.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } else if ($quantity <= 0) {
    $error_message = "Quantity must be greater than 0.";
  } else if ($product_id <= 0) {
    $error_message = "Invalid product ID.";
  }
  else {

    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO orders (name, email, quantity, product_id) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ssii", $name, $email, $quantity, $product_id);

      // Execute the statement
      if ($stmt->execute()) {
        $success_message = "Order placed successfully!";
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
