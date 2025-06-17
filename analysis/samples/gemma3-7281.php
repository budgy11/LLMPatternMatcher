

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize input (VERY IMPORTANT)
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
  $product_id = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);

  // Check if required fields are filled
  if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
    $error = "Please fill in all the required fields.";
  } else {
    // Validate email format (more robust validation can be added)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Invalid email format.";
    }
    // Validate quantity (e.g., ensure it's a positive integer)
    if ($quantity <= 0) {
      $error = "Please enter a valid positive quantity.";
    }
    // Validate product ID
    if (!is_numeric($product_id)) {
        $error = "Invalid product ID. Must be a number.";
    }

    // If no errors, proceed with the purchase
    else {
      // Database query (using prepared statements - crucial for security!)
      $conn = new mysqli($host, $username, $password, $database);

      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "INSERT INTO orders (name, email, product_id, quantity) VALUES (?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssii", $name, $product_id, $quantity);
      $stmt->execute();

      if ($stmt) {
        $success = "Purchase successful! Thank you, " . $name . "!";
        $stmt->close();
      } else {
        $error = "Error processing your purchase.";
        $stmt->close();
      }
    }
  }
}
?>
