

<?php

// Initialize session
session_start();

// Cart array (this will hold the items in the cart)
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the cart total
function calculateCartTotal() {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Example Usage (Simulating a simple shopping cart interaction) ---

// 1. Add an item to the cart
addToCart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, $1200, Quantity 1
addToCart(2, "Mouse", 25, 2); // Product ID 2, Mouse, $25, Quantity 2

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateCartTotal() . "</strong></p>";
}

// 2. Remove an item from the cart
// removeFromCart(1);  // Remove the Laptop

// 3. Update the quantity of an item
// updateQuantity(2, 3); // Change the Mouse quantity to 3

// Display the cart contents again (to see the changes)
echo "<h2>Shopping Cart (Updated)</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateCartTotal() . "</strong></p>";
}

?>
