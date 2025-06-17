
  <hr>

  <p><a href="checkout.php">Checkout</a></p>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate inputs
  $product_name = $_POST["product_name"];
  $quantity = $_POST["quantity"];
  $price = $_POST["price"];

  // Validate inputs - Important for security and data integrity
  if (empty($product_name) || empty($quantity) || empty($price)) {
    $error = "All fields are required.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error = "Quantity must be a positive number.";
  } elseif (!is_numeric($price) || $price <= 0) {
    $error = "Price must be a positive number.";
  } else {
    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO purchases (product_name, quantity, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $product_name, $quantity, $price);
    $stmt->execute();

    // Success message
    $message = "Purchase successful! Product: " . $product_name . ", Quantity: " . $quantity . ", Price: " . $price;

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
