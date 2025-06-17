

<?php
session_start();

// -----------------------------------------------------
//  Cart Functions
// -----------------------------------------------------

// Add an item to the cart
function addToCart($product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Initialize the cart array
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

// Update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Get the cart contents
function getCart() {
  return $_SESSION['cart'] ?? []; // Return the cart or an empty array if it doesn't exist
}

// Calculate the cart total
function calculateCartTotal() {
  $total = 0;
  $cart = getCart();
  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price']; // Assuming each item has a 'price' key
  }
  return $total;
}


// -----------------------------------------------------
//  Example Usage (Simulated Product Data - Replace with your actual product data)
// -----------------------------------------------------

$products = [
  1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// -----------------------------------------------------
//  Example Interaction - Simulating User Actions
// -----------------------------------------------------

// 1. User adds a T-Shirt (quantity 2)
addToCart(1, 2);

// 2. User updates the Jeans quantity to 1
updateCartQuantity(2, 1);

// 3. User removes the Hat
removeCartItem(3);

// 4. Display the cart contents
$cart = getCart();
echo "<h2>Your Cart:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    $product = $products[$product_id];
    echo "<li>" . $product['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['quantity'] * $product['price'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateCartTotal() . "</strong></p>";
}
?>
