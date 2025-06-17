
<h2>Add to Cart</h2>
<form method="POST" action="">
    <label for="item_id">Item ID:</label>
    <input type="number" id="item_id" name="item_id" required><br><br>
    <label for="item_name">Item Name:</label>
    <input type="text" id="item_name" name="item_name" required><br><br>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required><br><br>
    <input type="submit" value="Add to Cart" name="add_to_cart">
</form>

</body>
</html>


<?php

// Database Connection (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Basic example - Enhance for production)
  $product_name = trim($_POST["product_name"]);
  $quantity = intval($_POST["quantity"]);
  $price = floatval($_POST["price"]);

  // Check if required fields are filled and valid
  if (empty($product_name) || $quantity <= 0 || $price <= 0) {
    $error = "Please fill in all fields and ensure quantity and price are positive numbers.";
  } else {
    // Process the purchase
    $total_amount = $quantity * $price;

    //  In a real application, you would:
    // 1.  Save the order details to a database.
    // 2.  Update the product stock.
    // 3.  Potentially send an email confirmation.
    // This is a simplified example.

    echo "<p>Order Placed Successfully!</p>";
    echo "<p>Product: " . $product_name . "</p>";
    echo "<p>Quantity: " . $quantity . "</p>";
    echo "<p>Price per item: $" . $price . "</p>";
    echo "<p>Total Amount: $" . $total_amount . "</p>";
  }
}
?>
