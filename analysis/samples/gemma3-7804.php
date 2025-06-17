

<?php

// Database connection (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shop";
$db_user = "your_user";
$db_password = "your_password";

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to handle the purchase process
function processPurchase($cart, $payment_details) {
  // 1. Validate Cart Data
  if (empty($cart)) {
    return false; // Cart is empty
  }

  // 2. Update Inventory (Example - modify to fit your database)
  foreach ($cart as $product_id => $quantity) {
    // In a real system, you'd query your database to check stock
    // and update the inventory.  This is a simplified example:
    // Simulate stock deduction
    $stock = $stock - $quantity; 
    // Update the database here
    // Example:  $conn->query("UPDATE products SET stock = $stock WHERE id = $product_id");
  }

  // 3. Process Payment (Example - using a placeholder)
  if (processPayment($payment_details)) {
    // 4.  Create Order in Database (Example)
    $order_id = generateOrderId(); // Generate a unique order ID

    $order_data = [
      'order_id' => $order_id,
      'user_id' => $user_id, // Assuming you have a user ID
      'total_amount' => calculateTotal($cart),
      'order_date' => date('Y-m-d H:i:s'),
      'order_status' => 'pending'
    ];

    // Insert the order into the database
    // Example: $conn->query("INSERT INTO orders (order_id, user_id, total_amount, order_date, order_status) VALUES ($order_id, $user_id, $total_amount, '$order_date', '$order_status')");

    // 5.  Clear Cart (Important!)
    $cart = [];

    return true; // Purchase successful
  } else {
    return false; // Payment failed
  }
}

// Example Payment Processing (Placeholder - Replace with your payment gateway)
function processPayment($payment_details) {
  // In a real system, you'd integrate with a payment gateway (Stripe, PayPal, etc.)
  // This is a placeholder to demonstrate the process.
  // For testing, you could simply return true or false.

  // Simulate payment processing
  $payment_success = rand(0, 1); // 50% chance of success for testing.  DO NOT USE THIS IN PRODUCTION!

  // You would typically interact with your payment gateway API here
  // to process the payment.

  return $payment_success;
}

// Function to generate a unique order ID
function generateOrderId() {
  return bin2hex(random_bytes(16)); // Generate a random hexadecimal string.  Use a more robust method in production.
}

// Function to calculate the total amount of the cart
function calculateTotal($cart) {
  $total = 0;
  foreach ($cart as $product_id => $quantity) {
    // Assume you have a product table with price
    // $product_price = $conn->query("SELECT price FROM products WHERE id = $product_id")->fetch_assoc()['price'];
    $product_price = 10; // Replace with actual product price from your database
    $total += $product_price * $quantity;
  }
  return $total;
}



// ---  Example Usage (Simulated Data ) ---
// This section simulates a user adding items to their cart.
// In a real application, this data would come from a user interaction.

$cart = [
  1 => 2, // Product ID 1, quantity 2
  2 => 1, // Product ID 2, quantity 1
];

$payment_details = [
  'card_number' => '1234567890123456',
  'expiry_date' => '12/24',
  'cvv' => '123'
];

if (processPurchase($cart, $payment_details)) {
  echo "Purchase successful! Order ID: " . generateOrderId();
} else {
  echo "Purchase failed.  Please try again.";
}

?>
