
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

    <h1>Purchase Order</h1>

    <form method="post" action="">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required><br><br>

        <button type="submit">Place Order</button>
    </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Important for security)
  $name = trim($_POST["name"]);
  $quantity = intval($_POST["quantity"]);
  $price = floatval($_POST["price"]);

  if (empty($name) || $quantity <= 0 || $price <= 0) {
    $error = "Please fill in all fields and ensure quantity and price are positive numbers.";
  } else {
    // Process the purchase
    $total = $quantity * $price;

    // Add the purchase to the database (Simplified Example)
    try {
      $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $pdo->prepare("INSERT INTO purchases (name, quantity, price, total) VALUES (:name, :quantity, :price, :total)");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':total', $total);
      $stmt->execute();

      $message = "Purchase successful!  Your order has been recorded.";

    } catch (PDOException $e) {
      $message = "Error: " . $e->getMessage();
    } finally {
      $pdo = null;  // Close the database connection
    }
  }
}
?>
