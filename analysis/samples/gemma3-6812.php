

<?php

// Database connection (Replace with your actual database credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Handles a purchase request.
 *
 * @param array $cart_items An array of product IDs and quantities from the shopping cart.
 * @return string  A message indicating the success or failure of the purchase.
 */
function processPurchase(array $cart_items) {
  // 1. Validate Cart Items
  foreach ($cart_items as $item) {
    if (!is_array($item) || !isset($item['product_id']) || !isset($item['quantity']) || $item['quantity'] <= 0) {
      return "Invalid cart item format.  Please ensure each item is an array with 'product_id' and 'quantity' fields, and the quantity is greater than 0.";
    }
  }

  // 2.  Create Order Data
  $order_id = bin2hex(random_bytes(16)); // Generate a unique order ID (using random_bytes for security)
  $customer_id = "guest"; // You can implement customer registration and ID generation here.
  $order_date = date("Y-m-d H:i:s");

  // 3.  Fetch Product Details
  $products = [];
  foreach ($cart_items as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];

    // Retrieve product details from your product table
    $product_query = "SELECT id, name, price FROM products WHERE id = '$product_id'";
    $product_result = $conn->query($product_query);

    if ($product_result->num_rows > 0) {
      $product = $product_result->fetch_assoc();
      $products[] = $product;
    } else {
      return "Product ID '$product_id' not found in the product catalog.";
    }
  }

  // 4. Calculate Total Amount
  $total_amount = 0;
  foreach ($products as $product) {
    $total_amount += $product['price'] * $item['quantity'];
  }

  // 5.  Insert Order into Order Table
  $order_insert_query = "INSERT INTO orders (order_id, customer_id, order_date, total_amount) VALUES ('$order_id', '$customer_id', '$order_date', '$total_amount')";
  if (!$conn->query($order_insert_query)) {
    return "Error inserting order: " . $conn->error;
  }

  // 6. Insert Order Items into Order Items Table
  $order_items_insert_query = "INSERT INTO order_items (order_id, product_id, quantity) ";
  $order_items_insert_query .= "VALUES ";
  for ($i = 0; $i < count($cart_items); $i++) {
    $product_id = $cart_items[$i]['product_id'];
    $quantity = $cart_items[$i]['quantity'];
    $order_items_insert_query .= "('$order_id', '$product_id', '$quantity')";
    if ($i < count($cart_items) - 1) {
      $order_items_insert_query .= ", ";
    }
  }

  if (!$conn->query($order_items_insert_query)) {
    return "Error inserting order items: " . $conn->error;
  }

  // 7. Clear Cart (Simulate Clearing -  This is just a simple example)
  //   In a real application, you'd clear the cart from the user's session or database.
  session_start();
  $_SESSION['cart'] = [];

  // 8. Return Success Message
  return "Purchase successful! Order ID: " . $order_id;
}


// --- Example Usage (Simulate Cart Data) ---
$cart = [
  ['product_id' => '1', 'quantity' => 2],
  ['product_id' => '2', 'quantity' => 1],
];

$purchase_result = processPurchase($cart);
echo $purchase_result . "<br>";


// --- Database Setup (Simplified for Example) ---

// Create database (if it doesn't exist)
$sql_create_db = "CREATE DATABASE your_database";
if (!$conn->query($sql_create_db)) {
    die("Database creation failed: " . $conn->error);
}

// Create tables (if they don't exist)
$sql_create_orders = "CREATE TABLE IF NOT EXISTS orders (
    order_id BINARY(16) PRIMARY KEY,
    customer_id VARCHAR(255) NOT NULL,
    order_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL
)";

$sql_create_order_items = "CREATE TABLE IF NOT EXISTS order_items (
    order_id BINARY(16) NOT NULL,
    product_id VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
)";

if (!$conn->query($sql_create_orders)) {
    die("Error creating orders table: " . $conn->error);
}

if (!$conn->query($sql_create_order_items)) {
    die("Error creating order_items table: " . $conn->error);
}

// Create sample product table
$sql_create_products = "CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
)";
if (!$conn->query($sql_create_products)) {
    die("Error creating products table: " . $conn->error);
}

// Insert sample products
$sql_insert_product_1 = "INSERT INTO products (id, name, price) VALUES ('1', 'Product A', 10.00)";
$sql_insert_product_2 = "INSERT INTO products (id, name, price) VALUES ('2', 'Product B', 20.00)";

if (!$conn->query($sql_insert_product_1)) {
    die("Error inserting product 1: " . $conn->error);
}

if (!$conn->query($sql_insert_product_2)) {
    die("Error inserting product 2: " . $conn->error);
}

?>
