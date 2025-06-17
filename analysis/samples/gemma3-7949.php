    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (crucial for security)
  $name = $_POST["name"];
  $quantity = $_POST["quantity"];
  $price = $_POST["price"];

  if (empty($name) || empty($quantity) || empty($price)) {
    $error = "Please fill in all fields.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error = "Quantity must be a positive number.";
  } elseif (!is_numeric($price) || $price <= 0) {
    $error = "Price must be a positive number.";
  } else {
    // Sanitize data (important to prevent SQL injection)
    $name = mysqli_real_escape_string($connection, $name);
    $quantity = intval($quantity); // Convert to integer
    $price = floatval($price);  // Convert to float

    // SQL query to insert the purchase into the database
    $sql = "INSERT INTO purchases (product_name, quantity, price) VALUES ('$name', $quantity, $price)";

    if (mysqli_query($connection, $sql)) {
      $success = "Purchase added successfully!";
    } else {
      $error = "Error adding purchase: " . mysqli_error($connection);
    }
  }
}

// Connect to the database
$connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check the connection
if (!$connection) {
  die("Connection failed: " . mysqli_connect_error());
}

?>
