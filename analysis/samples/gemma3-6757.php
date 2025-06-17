    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerceshop";
$db_user = "root";
$db_password = "";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize inputs
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];

  // Input validation - Add more robust validation here for production
  if (!is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
    $error = "Invalid product ID or quantity.";
  } else {
    // Add the order to the database
    $sql = "INSERT INTO orders (product_id, quantity) VALUES ('$product_id', '$quantity')";

    if ($conn->query($sql)) {
      $success = "Order placed successfully!";
    } else {
      $error = "Error: " . $conn->error;
    }
  }
}

// Retrieve product details - This is for displaying on the purchase page
$product_id = $_POST["product_id"];
$sql = "SELECT * FROM products WHERE id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $product = $result->fetch_assoc();
  $product_name = $product["name"];
  $product_price = $product["price"];
} else {
  $product_name = "Product Not Found";
  $product_price = 0;
}

?>
