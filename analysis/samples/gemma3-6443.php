

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Product Information (Example Data - Replace with your actual database/API calls) ---
$products = array(
  array(
    'id' => 1,
    'name' => 'Laptop',
    'price' => 1200
  ),
  array(
    'id' => 2,
    'name' => 'Mouse',
    'price' => 25
  ),
  array(
    'id' => 3,
    'name' => 'Keyboard',
    'price' => 75
  )
);

// --- Function to add an item to the cart ---
function addToCart($product_id, $quantity = 1) {
  global $products;

  // Find the product
  $product = null;
  foreach ($products as $p) {
    if ($p['id'] == $product_id) {
      $product = $p;
      break;
    }
  }

  if ($product) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      // Add the product to the cart
      $_SESSION['cart'][$product_id] = array(
        'quantity' => $quantity,
        'name' => $product['name'],
        'price' => $product['price']
      );
    }
  }
}

// --- Handling Add to Cart Requests (Example - via form submission) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']); // Convert to integer
    $quantity = intval($_POST['quantity']);    // Convert to integer
    addToCart($product_id, $quantity);
  }
}

// --- Displaying the Cart ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " - Total: $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate total price
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $total += $item['price'] * $item['quantity'];
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

?>
