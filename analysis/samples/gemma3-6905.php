  <p>Total Cart Value: $<?php echo round(array_sum(array_column($cart, 'total')), 2); ?></p>

</body>
</html>


<?php

// Database connection (Replace with your database details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//  Example Product Data (For demonstration - use a database instead)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];


// Function to handle the purchase process
function processPurchase($productId, $quantity) {
  // Validate inputs
  if (!is_numeric($productId) || $productId <= 0 || !is_numeric($quantity) || $quantity <= 0) {
    return "Invalid product ID or quantity.";
  }

  // Find the product
  $product = null;
  foreach ($products as $id => $item) {
    if ($id == $productId) {
      $product = $item;
      break;
    }
  }

  if ($product === null) {
    return "Product not found.";
  }

  // Calculate total price
  $totalPrice = $product['price'] * $quantity;

  // You would typically:
  // 1. Update inventory
  // 2. Record the order in a database
  // 3. Send a confirmation email
  // For this example, we'll just log the purchase to the console.

  echo "<h2>Purchase Details</h2>";
  echo "<p>Product: " . $product['name'] . "</p>";
  echo "<p>Quantity: " . $quantity . "</p>";
  echo "<p>Total Price: $" . number_format($totalPrice, 2) . "</p>";

  // Log the purchase (Replace with database insertion)
  $logMessage = date("Y-m-d H:i:s") . " - User purchased " . $quantity . " " . $product['name'] . " for $" . number_format($totalPrice, 2) . "
";
  error_log($logMessage, 3, 'purchase_log.txt'); // Logs to purchase_log.txt
  //You'd typically store the order details in a database table.


  return "Purchase successful!  Check your email for confirmation.";
}


// Handle form submission (if you have a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $productId = $_POST["product_id"];
  $quantity = $_POST["quantity"];

  $purchaseResult = processPurchase($productId, $quantity);
  echo $purchaseResult;
}
?>
