
    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="address">Shipping Address:</label>
    <textarea id="address" name="address" rows="4" cols="50" required></textarea><br><br>

    <button type="submit" name="submit_order">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize inputs
  $product_name = htmlspecialchars($_POST["product_name"]);
  $product_price = floatval($_POST["product_price"]);
  $quantity = intval($_POST["quantity"]);

  // Error handling
  $errors = [];

  if (empty($product_name)) {
    $errors[] = "Product name cannot be empty.";
  }
  if ($product_price <= 0) {
    $errors[] = "Product price must be greater than zero.";
  }
  if ($quantity <= 0) {
    $errors[] = "Quantity must be greater than zero.";
  }

  // If there are no errors, proceed with the purchase
  if (empty($errors)) {

    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO purchases (product_name, product_price, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $product_name, $product_price, $quantity);
    $stmt->execute();

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();

    // Display success message
    echo "<p style='color: green;'>Purchase successful! Product: " . $product_name . ", Quantity: " . $quantity . ", Price: " . $product_price . "</p>";

  } else {
    // Display error messages
    echo "<div style='color: red;'>";
    foreach ($errors as $error) {
      echo $error . "<br>";
    }
    echo "</div>";
  }
}
?>
