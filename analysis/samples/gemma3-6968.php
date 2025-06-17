

<?php

// Assuming you have a database connection established (e.g., mysqli)
// For demonstration purposes, let's assume we're using a simple array for product data.
$products = [
  1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 10],
  2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00, 'quantity' => 5],
  3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00, 'quantity' => 20],
];


// Function to handle the purchase process
function handlePurchase($cart, $dbConnection) {
  $total = 0;
  $order_id = 0;

  // Validate cart and products
  if (empty($cart)) {
    return ['success' => false, 'message' => 'Cart is empty.'];
  }

  // Check if all products in cart exist
  foreach ($cart as $product_id => $quantity) {
    if (!isset($products[$product_id])) {
      return ['success' => false, 'message' => "Product ID $product_id not found."];
    }
  }


  // Prepare the order data
  $order_data = [
    'user_id' => 123, // Assuming a user ID of 123 for demonstration
    'order_date' => date('Y-m-d H:i:s'),
    'total_amount' => 0
  ];

  // Create the order in the database
  $sql = "INSERT INTO orders (user_id, order_date) VALUES (?, ?)";
  $stmt = $dbConnection->prepare($sql);
  $stmt->bind_param("is", $order_data['user_id'], $order_data['order_date']);
  $stmt->execute();
  $order_id = $dbConnection->insert_id; // Get the ID of the newly inserted order
  $stmt->close();

  // Loop through the cart and add items to the order
  foreach ($cart as $product_id => $quantity) {
    $product = $products[$product_id];
    $product_price = $product['price'];
    $total_amount = $total_amount + ($product_price * $quantity);
    $product_data = [
      'order_id' => $order_id,
      'product_id' => $product_id,
      'quantity' => $quantity,
      'price' => $product_price
    ];
    
    // Insert order items into the database
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bind_param("isii", $stmt->stmt_param, $stmt->stmt_param, $stmt->stmt_param, $stmt->stmt_param);
    $stmt->execute();
    $stmt->close();
  }

  $order_data['total_amount'] = $total_amount;
  return ['success' => true, 'order_id' => $order_id, 'total_amount' => $total_amount];
}

// -----------------------  Example Usage  -----------------------

// Assuming a database connection is already established
// Replace with your actual database connection details
$dbConnection = new mysqli("localhost", "username", "password", "database_name");

if ($dbConnection->connect_error) {
  die("Connection failed: " . $dbConnection->connect_error);
}

// 1. Get Cart Data (e.g., from a form)
// In a real application, you'd retrieve this from a shopping cart.
// For this example, we'll create a sample cart.
$cart = [
  1 => 2, // 2 T-Shirts
  2 => 1  // 1 Jeans
];

// 2. Process the Purchase
$purchaseResult = handlePurchase($cart, $dbConnection);

// 3. Display Results
if ($purchaseResult['success']) {
  echo "<h2>Purchase Successful!</h2>";
  echo "<p>Order ID: " . $purchaseResult['order_id'] . "</p>";
  echo "<p>Total Amount: $" . $purchaseResult['total_amount'] . "</p>";
  echo "<p>Thank you for your order!</p>";
} else {
  echo "<h2>Purchase Failed!</h2>";
  echo "<p>Error: " . $purchaseResult['message'] . "</p>";
}

$dbConnection->close();
?>
