        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required><br><br>

        <input type="submit" value="Create Purchase">
    </form>

</body>
</html>


<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Handle the purchase process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 1. Validate Form Data
  $order_id = $_POST["order_id"];
  $total_amount = $_POST["total_amount"];
  $shipping_address = $_POST["shipping_address"];
  $payment_method = $_POST["payment_method"]; // e.g., "credit_card", "paypal"

  // Basic validation -  add more robust validation as needed
  if (empty($order_id) || empty($total_amount) || empty($shipping_address) || empty($payment_method)) {
    $error_message = "All fields are required.";
  } else {
    // 2. Process the Order -  This is the core logic
    // Example:  Add order details to the database

    try {
      // Connect to the database
      $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);

      // Set PDO error mode to exception
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Example: Insert order details into the database
      $sql = "INSERT INTO orders (order_id, total_amount, shipping_address, payment_method, order_date) VALUES (:order_id, :total_amount, :shipping_address, :payment_method, NOW())";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':order_id', $order_id);
      $stmt->bindParam(':total_amount', $total_amount);
      $stmt->bindParam(':shipping_address', $shipping_address);
      $stmt->bindParam(':payment_method', $payment_method);
      $stmt->execute();

      // Optionally, you could:
      // - Send an email confirmation
      // - Update inventory
      // - Generate a receipt

      $success_message = "Order placed successfully! Order ID: " . $order_id;

    } catch (PDOException $e) {
      $error_message = "Database error: " . $e->getMessage();
    }
  }
}

// Retrieve cart data (assuming you have a cart system - this is a placeholder)
// You'll need to implement your actual cart retrieval logic here.
$cart_items = [
  ['product_id' => 1, 'name' => 'T-Shirt', 'quantity' => 2, 'price' => 20],
  ['product_id' => 2, 'name' => 'Jeans', 'quantity' => 1, 'price' => 50],
];

$total_cart_amount = 0;
foreach ($cart_items as $item) {
  $total_cart_amount += $item['quantity'] * $item['price'];
}


?>
