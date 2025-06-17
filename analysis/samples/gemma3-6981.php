
</body>
</html>


<?php

// Database connection (replace with your actual database credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate form data (important for security!)
  $product_name = trim($_POST["product_name"]);
  $product_price = floatval($_POST["product_price"]);
  $quantity = intval($_POST["quantity"]);

  // Check if required fields are filled
  if (empty($product_name) || $product_price <= 0 || $quantity <= 0) {
    $error_message = "Please fill in all fields correctly.";
  } else {
    // Create a database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO products (product_name, product_price, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("sii", $product_name, $product_price, $quantity);
      if ($stmt->execute()) {
        $success_message = "Product added successfully!";
      } else {
        $error_message = "Error adding product: " . $stmt->error;
      }
      $stmt->close();
    } else {
      $error_message = "Error preparing statement.";
    }

    $conn->close();
  }
}
?>
