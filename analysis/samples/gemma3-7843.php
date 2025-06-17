

<?php

// Database connection details
$dbHost = "localhost";
$dbUsername = "your_username";
$dbPassword = "your_password";
$dbName = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate form data (basic example - improve for production)
  $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
  $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
  $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0;

  // Check if all required fields are filled
  if (empty($name) || empty($email) || $quantity <= 0) {
    $error = "Please fill in all required fields.";
  } else {
    // Sanitize input (IMPORTANT for security - use more robust validation/escaping)
    $name = htmlspecialchars($name);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Prepare SQL query - IMPORTANT for security (prevent SQL injection)
    $sql = "INSERT INTO orders (name, email, quantity) VALUES (?, ?, ?)";

    // Use prepared statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $email, $quantity);

    if ($stmt->execute()) {
      $success = "Order placed successfully! Order ID: " . $conn->insert_id;
    } else {
      $error = "An error occurred while placing the order: " . $stmt->error;
    }

    $stmt->close();
  }
}

// Database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
