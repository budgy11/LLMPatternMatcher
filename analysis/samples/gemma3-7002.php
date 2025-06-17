

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate form data
  $name = $_POST["name"];
  $email = $_POST["email"];
  $quantity = $_POST["quantity"];
  $product_id = $_POST["product_id"]; // Assuming product_id is passed from a product selection

  // Sanitize and validate input (Crucial for security!)
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $quantity = filter_int(filter_input(INPUT_POST, "quantity", FILTER_SANITIZE_NUMBER_INT)); // Convert to integer, sanitize

  if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
    $error = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif ($quantity <= 0) {
    $error = "Quantity must be a positive number.";
  } else {
    // If validation passes, proceed with order processing

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL query
    $sql = "INSERT INTO orders (name, email, product_id, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $name, $product_id, $quantity);
    $stmt->execute();

    // Success message
    $message = "Order placed successfully! Thank you.";

    // Close statement and connection
    $stmt->close();
    $conn->close();

  }
}
?>
