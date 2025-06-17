    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <input type="submit" value="Add to Cart">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "e_commerce_db";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate input
  $product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : 0;
  $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

  // Check if product_id and quantity are valid
  if ($product_id > 0 && $quantity > 0) {

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Get product details
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $product = $result->fetch_assoc();
      $product_name = $product["name"];
      $product_price = $product["price"];
      $total_amount = $product_price * $quantity;

      // Create the order
      $sql_insert = "INSERT INTO orders (product_id, quantity, total_amount, order_date) VALUES (?, ?, ?, NOW())";
      $stmt_insert = $conn->prepare($sql_insert);
      $stmt_insert->bind_param("iis", $product_id, $quantity, $total_amount);
      $stmt_insert->execute();

      // Display confirmation message
      echo "<div class='success'>
              <h2>Order Placed Successfully!</h2>
              <p>You ordered " . $quantity . " " . $product_name . " for $" . $total_amount . "</p>
              <p>Your order ID is: " . $conn->insert_id . "</p>
              <a href='index.php'>Continue Shopping</a>
            </div>";
      
      // Optionally, you can update inventory here:
      //  $sql_update_inventory = "UPDATE products SET stock -= ? WHERE id = ?";
      //  $stmt_update = $conn->prepare($sql_update_inventory);
      //  $stmt_update->bind_param("is", -$quantity, $product_id);
      //  $stmt_update->execute();

    } else {
      echo "<div class='error'>
                <h2>Product Not Found!</h2>
                <p>The product with ID " . $product_id . " does not exist.</p>
              </div>";
    }
    $stmt->close();
    $conn->close();
  } else {
    // Invalid input
    echo "<div class='error'>
              <h2>Invalid Input!</h2>
              <p>Please enter valid product ID and quantity.</p>
            </div>";
  }
}
?>
