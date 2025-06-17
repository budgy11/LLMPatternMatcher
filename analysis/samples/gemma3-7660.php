

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "e_commerce";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    // Handle the database connection error
    echo "Database connection failed: " . $e->getMessage();
    exit;
  }
}

// Function to handle the purchase process
function handlePurchase($cart_id, $payment_method, $shipping_address) {
  // Connect to the database
  $pdo = connectToDatabase();

  // Check if the cart exists
  $stmt = $pdo->prepare("SELECT * FROM carts WHERE id = ?");
  $stmt->execute([$cart_id]);
  $cart = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$cart) {
    echo "Cart not found.";
    return false;
  }

  // Validate cart contents (e.g., check if products in the cart exist)
  // You would ideally perform more thorough validation here.

  // Prepare the order data
  $order_date = date("Y-m-d H:i:s");
  $total_amount = calculateTotalAmount($cart);

  // Insert the order into the orders table
  $stmt = $pdo->prepare(
    "INSERT INTO orders (cart_id, order_date, total_amount, payment_method, shipping_address)
    VALUES (?, ?, ?, ?, ?)"
  );
  $stmt->execute([$cart_id, $order_date, $total_amount, $payment_method, $shipping_address]);

  // Update the cart to mark it as purchased
  $stmt = $pdo->prepare("UPDATE carts SET purchased = 1, order_id = ? WHERE id = ?");
  $stmt->execute([$pdo->lastInsertId(), $cart_id]); // Get the last inserted order ID

  // (Optional) Clear the cart after a successful purchase
  // $stmt = $pdo->prepare("DELETE FROM carts WHERE id = ?");
  // $stmt->execute([$cart_id]);

  echo "Purchase successful! Order ID: " . $pdo->lastInsertId();
  return true;
}


// Function to calculate the total amount of the cart
function calculateTotalAmount($cart) {
  $total = 0;
  foreach ($cart['items'] as $item) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$item['product_id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $total + ($product['price'] * $item['quantity']);
  }
  return $total;
}


// --- Example Usage (within a form or as part of a larger application) ---

// Get cart ID from URL or form input
$cart_id = isset($_GET['cart_id']) ? $_GET['cart_id'] : null;

if ($cart_id) {
  // Get payment method and shipping address from form inputs
  $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'Credit Card';
  $shipping_address = isset($_POST['shipping_address']) ? $_POST['shipping_address'] : 'Default Address';

  // Handle the purchase
  handlePurchase($cart_id, $payment_method, $shipping_address);
} else {
  echo "Invalid cart ID. Please provide a valid cart ID.";
}


// --- Database Setup (Example - Create Tables) ---
// This part is for setting up the database.  Run this *once* to create the tables.
/*
$pdo = connectToDatabase();

$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS carts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  purchased BOOLEAN DEFAULT 0,
  order_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$stmt->execute();

$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cart_id INT NOT NULL,
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total_amount DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(255) NOT NULL,
  shipping_address TEXT NULL,
  FOREIGN KEY (cart_id) REFERENCES carts(id)
)");
$stmt->execute();
*/

?>
