    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="item_id">Item ID:</label>
    <input type="number" id="item_id" name="item_id" min="1" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate the data
  $product_name = $_POST["product_name"];
  $quantity = $_POST["quantity"];
  $price = $_POST["price"]; // Assuming price is already in a numeric format

  if (empty($product_name) || empty($quantity) || empty($price)) {
    $error = "Please fill in all fields.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error = "Quantity must be a positive number.";
  } elseif (!is_numeric($price) || $price <= 0) {
    $error = "Price must be a positive number.";
  } else {
    // Prepare the SQL statement
    $sql = "INSERT INTO orders (product_name, quantity, price) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sdd", $product_name, $quantity, $price);

    // Execute the statement
    if ($stmt->execute()) {
      $success = "Order placed successfully!";
    } else {
      $error = "Order placement failed: " . $stmt->error;
    }
  }
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>
