

<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (Important for security)
  $product_name = $_POST["product_name"];
  $quantity = intval($_POST["quantity"]);
  $price = floatval($_POST["price"]);

  // Input validation - essential for security
  if (empty($product_name) || $quantity <= 0 || $price <= 0) {
    $error_message = "Please fill in all fields and ensure quantities and prices are positive.";
  } else {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO cart (product_name, quantity, price) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $product_name, $quantity, $price);

    // Execute the statement
    if ($stmt->execute()) {
      $success_message = "Product added to cart!";
    } else {
      $error_message = "Error adding product to cart.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}

?>
