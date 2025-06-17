

<?php
session_start();

// --- Database Connection (Replace with your database details) ---
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// --------------------------------------------------------------

// --- Product Data (Replace with your actual product data source) ---
$products = array(
  1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200, 'description' => 'Powerful laptop for work and play.'),
  2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25, 'description' => 'Ergonomic wireless mouse.'),
  3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75, 'description' => 'Mechanical keyboard for a superior typing experience.'),
  4 => array('id' => 4, 'name' => 'Monitor', 'price' => 300, 'description' => '27-inch LED monitor.'),
);
// --------------------------------------------------------------

// --- Cart Functionality ---

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to Cart
function addToCart($product_id, $quantity) {
  global $conn, $products;

  // Check if the product exists
  if (isset($products[$product_id])) {
    $product = $products[$product_id];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      $_SESSION['cart'][$product_id] = array(
        'id' => $product_id,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'description' => $product['description']
      );
    }
  } else {
    echo "Product ID $product_id not found.";
  }
}

// Remove from Cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  } else {
    echo "Product ID $product_id not found in cart.";
  }
}

// Update Quantity in Cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        echo "Product ID $product_id not found in cart.";
    }
}

// Get Cart Contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Calculate Total Cart Value
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Displaying the Cart ---

// Check if there are items in the cart
$cart_items = getCartContents();
$total_value = calculateTotal();

echo "<h1>Shopping Cart</h1>";

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total_value . "</strong></p>";
}

// --- Example Buttons & Forms ---

echo "<br>";
echo "<a href='add_to_cart.php?product_id=1'>Add Laptop to Cart</a> | ";
echo "<a href='add_to_cart.php?product_id=2'>Add Mouse to Cart</a> | ";
echo "<a href='add_to_cart.php?product_id=3'>Add Keyboard to Cart</a>";

?>
