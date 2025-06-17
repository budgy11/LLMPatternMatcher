
</body>
</html>


<?php
session_start();

// Database connection (Replace with your actual credentials)
$dbHost = 'localhost';
$dbUser = 'your_db_user';
$dbPass = 'your_db_password';
$dbName = 'your_database_name';

// Connect to the database
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input (Important for security!)
function sanitizeInput($data) {
  global $conn;
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


// Handle the purchase request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = sanitizeInput($_POST["product_id"]);
  $quantity = sanitizeInput($_POST["quantity"]);
  $customer_name = sanitizeInput($_POST["customer_name"]);
  $customer_email = sanitizeInput($_POST["customer_email"]);

  // Validate input (Add more validation as needed)
  if (empty($product_id) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
    $error = "All fields are required.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error = "Invalid quantity. Please enter a positive number.";
  } else {
    // Prepare SQL query (Using prepared statements - VERY IMPORTANT!)
    $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email)
            VALUES (?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind parameters
      $stmt->bind_param("isss", $product_id, $quantity, $customer_name, $customer_email);

      // Execute the statement
      if ($stmt->execute()) {
        $success = "Order placed successfully!";
      } else {
        $error = "Error placing order: " . $stmt->error;
      }

      // Close the statement
      $stmt->close();
    } else {
      $error = "Error preparing statement.";
    }
  }
}

?>
