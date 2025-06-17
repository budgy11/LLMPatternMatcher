

<?php

// Database configuration (replace with your actual database details)
$db_host = 'localhost';
$db_name = 'shop';
$db_user = 'root';
$db_password = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (basic example, add more validation)
  $name = $_POST["name"];
  $email = $_POST["email"];
  $quantity = intval($_POST["quantity"]);
  $price = floatval($_POST["price"]);

  // Basic validation - more robust validation is crucial in real-world scenarios
  if (empty($name) || empty($email) || $quantity <= 0 || $price <= 0) {
    $error = "Please fill in all fields and ensure quantity and price are positive numbers.";
  } else {
    // Sanitize input (important for security)
    $name = htmlspecialchars($name);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $quantity = intval($quantity);
    $price = floatval($price);


    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO purchases (name, email, quantity, price) VALUES (?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind parameters
      $stmt->bind_param("ssid", $name, $email, $quantity, $price);

      // Execute the query
      if ($stmt->execute()) {
        $success = "Purchase successful! Thank you, " . $name . "!";
      } else {
        $error = "Query execution failed: " . $stmt->error;
      }

      // Close the statement
      $stmt->close();
    } else {
      $error = "Statement preparation failed.";
    }

    // Close the connection
    $conn->close();
  }
}
?>
