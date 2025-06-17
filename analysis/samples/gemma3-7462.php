
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Purchase Product</h1>

  <form method="post" action="">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <option value="1">Laptop</option>
      <option value="2">Mouse</option>
      <option value="3">Keyboard</option>
    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" min="1"><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" name="customer_name" id="customer_name"><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" name="customer_email" id="customer_email"><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual details)
$db_host = 'localhost';
$db_name = 'ecommerce_db';
$db_user = 'your_user';
$db_pass = 'your_password';

// Function to connect to the database
function connect_db() {
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add a new order
function add_order($user_id, $cart_id) {
  $conn = connect_db();

  // Check if the cart exists
  $check_cart_query = "SELECT id FROM carts WHERE id = $cart_id";
  $result = $conn->query($check_cart_query);

  if ($result->num_rows > 0) {
    // Insert the order into the orders table
    $order_query = "INSERT INTO orders (user_id, cart_id) VALUES ($user_id, $cart_id)";
    if ($conn->query($order_query) === TRUE) {
      return true; // Order added successfully
    } else {
      return false; // Error adding order
    }
  } else {
    return false; // Cart doesn't exist
  }
}

// Function to get cart details
function get_cart_details($cart_id) {
  $conn = connect_db();

  // Get the products from the cart
  $product_query = "SELECT p.id, p.name, p.price, p.image FROM carts c JOIN cart_items ci ON c.id = ci.cart_id JOIN products p ON ci.product_id = p.id WHERE c.id = $cart_id";
  $result = $conn->query($product_query);

  if ($result->num_rows > 0) {
    $cart_products = array();
    while ($row = $result->fetch_assoc()) {
      $cart_products[] = $row;
    }
    return $cart_products;
  } else {
    return []; // Empty cart
  }
}

// --- Example Usage (Handle form submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get user ID (example: from a logged-in session)
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;  //Replace with your method of getting user id

  // Get cart ID from the form
  $cart_id = $_POST['cart_id'];

  // Add the order
  if (add_order($user_id, $cart_id)) {
    echo "Order placed successfully! Cart ID: " . $cart_id;
    // Optionally, clear the cart after order placement
    // clear_cart($cart_id);
  } else {
    echo "Error placing order.";
  }
}

// -------------------  Database Tables (Illustrative) --------------------

// Example database schema -  Adapt to your needs
/*
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
);

CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (cart_id) REFERENCES carts(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    cart_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (cart_id) REFERENCES carts(id)
);
*/

?>
