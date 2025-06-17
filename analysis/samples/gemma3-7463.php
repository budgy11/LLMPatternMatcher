

<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "shop";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate inputs (IMPORTANT for security)
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $quantity = intval($_POST["quantity"]); // Ensure quantity is an integer
  $price = floatval($_POST["price"]);  // Ensure price is a float

  // Basic validation
  if (empty($name) || empty($email) || $quantity <= 0 || $price <= 0) {
    $error = "Please fill in all fields and ensure values are positive.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } else {
    // Process the order
    // 1.  Create a database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // 2. Prepare and execute the SQL query
    $sql = "INSERT INTO orders (name, email, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("ssdd", $name, $email, $quantity, $price); // Correctly bind parameters

      if ($stmt->execute()) {
        $success = "Order placed successfully! Order ID: " . $conn->insert_id;
      } else {
        $error = "Order placement failed: " . $stmt->error;
      }

      $stmt->close();
    } else {
      $error = "Error preparing statement.";
    }

    $conn->close();
  }
}

?>
