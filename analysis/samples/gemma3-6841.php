

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (This is crucial to prevent security vulnerabilities)
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $quantity = intval($_POST["quantity"]); // Ensure quantity is an integer
  $product_id = intval($_POST["product_id"]); // Ensure product_id is an integer
  // Add more validation as needed for other fields

  // Perform input validation - Basic example, expand as needed
  if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif ($quantity <= 0) {
    $error = "Quantity must be a positive integer.";
  } else {
    // Process the purchase
    // 1. Create a database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // 2. Prepare the SQL query
    $sql = "INSERT INTO orders (name, email, quantity, product_id) VALUES (?, ?, ?, ?)";

    // 3. Prepare the statement
    $stmt = $conn->prepare($sql);

    // 4. Bind parameters (Important for security!)
    $stmt->bind_param("sii", $name, $quantity, $product_id);

    // 5. Execute the query
    if ($stmt->execute()) {
      $success = "Purchase successful! Order ID: " . $conn->insert_id;
    } else {
      $error = "Purchase failed: " . $stmt->error;
    }

    // 6. Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
