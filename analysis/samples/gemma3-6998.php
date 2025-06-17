    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" min="1" required><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate input
  $product_name = $_POST["product_name"];
  $product_price = floatval($_POST["product_price"]);
  $quantity = intval($_POST["quantity"]);

  // Basic validation
  if (empty($product_name) || $product_price <= 0 || $quantity <= 0) {
    $error = "Please fill in all fields and ensure prices and quantities are positive.";
  } else {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO purchases (product_name, product_price, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdd", $product_name, $product_price, $quantity);
    $stmt->execute();

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();

    // Display success message
    echo "<p style='color: green;'>Purchase added successfully!</p>";
  }
}
?>
