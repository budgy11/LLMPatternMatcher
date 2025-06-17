

<?php

// Start a session (if it doesn't already exist)
session_start();

// --- Cart Logic ---

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  // Check if the cart exists in the session.  If not, initialize it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart.
  if (isset($_SESSION['cart'][$product_id])) {
    // Product is already in the cart - increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product is not in the cart - add it with the specified quantity
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to clear the entire cart
function clearCart() {
    unset($_SESSION['cart']);
}

// --- Example Usage (Demonstration) ---

// Let's say we have these product IDs: 1, 2, 3

// 1. Add a product to the cart
addToCart(1, 2); // Add 2 of product ID 1
addToCart(2, 1); // Add 1 of product ID 2

// 2. Display the contents of the cart
echo "<h2>Your Shopping Cart:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// 3. Example: Remove a product
removeFromCart(2);

// 4. Display the cart again after removal
echo "<h2>Your Shopping Cart (after removal):</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// 5. Example: Update quantity
updateQuantity(1, 5); // Change quantity of product 1 to 5

// 6. Display the cart after update
echo "<h2>Your Shopping Cart (after update):</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// 7. Example: Clear the cart
//clearCart();
//echo "<p>Cart has been cleared.</p>";


?>
