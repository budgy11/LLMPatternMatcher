
<!DOCTYPE html>
<html>
<head>
  <title>Simple Purchase Functionality</title>
</head>
<body>

  <h1>Online Store</h1>

  <div id="product-list">
    <h2>Available Products:</h2>
    <ul>
      <li>
        Product 1 <button onclick="addToCart('product1', 'Product 1', 10.00, 1)">Add to Cart</button>
      </li>
      <li>
        Product 2 <button onclick="addToCart('product2', 'Product 2', 20.00, 1)">Add to Cart</button>
      </li>
      <li>
        Product 3 <button onclick="addToCart('product3', 'Product 3', 15.00, 1)">Add to Cart</button>
      </li>
    </ul>
  </div>

  <form method="post" action="purchase.php">
    <input type="hidden" name="product_id" value="product1"> <!-- Example: Default product -->
    <input type="submit" name="submit_order" value="Place Order">
  </form>

</body>
</html>


<?php

// --- Database Configuration ---
$dbHost = "localhost";
$dbName = "ecommerce";
$dbUser = "your_db_user";
$dbPass = "your_db_password";

// --- Check if the form has been submitted ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate Form Input (Basic Validation - Enhance for Production)
  $name = $_POST["name"];
  $email = $_POST["email"];
  $quantity = $_POST["quantity"];
  $product_id = $_POST["product_id"]; // Assuming you have a product ID

  // Check if inputs are valid (adjust validation as needed)
  if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (is_numeric($quantity) == false || $quantity <= 0) {
    $error = "Quantity must be a number greater than 0.";
  } else {
    // --- Process the Purchase ---
    // 1. Add Order to the Database
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO orders (name, email, quantity, product_id) VALUES ('" . $conn->real_escape_string($name) . "', '" . $conn->real_escape_string($email) . "', '" . $conn->real_escape_string($quantity) . "', '" . $conn->real_escape_string($product_id) . "')";

    if ($conn->query($sql) === TRUE) {
      $success = "Order placed successfully! Order ID: " . $conn->insert_id;
    } else {
      $error = "Error placing order: " . $conn->error;
    }

    $conn->close();
  }
}
?>
