
</body>
</html>


<?php

// Configuration (Database credentials and settings)
$db_host = "localhost";
$db_name = "ecommerce_db";
$db_user = "your_user";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (basic validation - improve this!)
  $name = isset($_POST["name"]) && !empty($_POST["name"]) ? trim($_POST["name"]) : "";
  $email = isset($_POST["email"]) && !empty($_POST["email"]) ? trim($_POST["email"]) : "";
  $quantity = isset($_POST["quantity"]) && is_numeric($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;  // Default to 1 if not provided.
  $product_id = isset($_POST["product_id"]) && is_numeric($_POST["product_id"]) ? intval($_POST["product_id"]) : 0; // Product ID
  $notes = isset($_POST["notes"]) && !empty($_POST["notes"]) ? trim($_POST["notes"]) : "";


  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the INSERT statement
  $sql = "INSERT INTO orders (name, email, quantity, product_id, notes) VALUES (?, ?, ?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ssii", $name, $email, $quantity, $product_id);

  // Execute the statement
  if ($stmt->execute()) {
    echo "Order placed successfully! Order ID: " . $conn->insert_id;
  } else {
    echo "Error: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
