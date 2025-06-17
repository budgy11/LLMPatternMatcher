

<?php
session_start();

// Define constants for cart keys
define('CART_KEY', 'shopping_cart');

// Function to add item to cart
function addToCart($product_id, $quantity) {
  global($CART_KEY);

  if (isset($_SESSION[$CART_KEY])) {
    $cart = $_SESSION[$CART_KEY];
  } else {
    $cart = array();
  }

  if (isset($cart[$product_id])) {
    $cart[$product_id] = $cart[$product_id] + $quantity;
  } else {
    $cart[$product_id] = $quantity;
  }
}

// Function to remove item from cart
function removeFromCart($product_id) {
  global($CART_KEY);

  if (isset($_SESSION[$CART_KEY])) {
    $cart = $_SESSION[$CART_KEY];
    unset($cart[$product_id]); //Use product_id here, not $product_id
  }
}

// Function to get cart contents
function getCartContents() {
  global($CART_KEY);
  if (isset($_SESSION[$CART_KEY])) {
    return $_SESSION[$CART_KEY];
  } else {
    return array();
  }
}

// Function to update quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  global($CART_KEY);

  if (isset($_SESSION[$CART_KEY])) {
    $cart = $_SESSION[$CART_KEY];
    if (isset($cart[$product_id])) {
      $cart[$product_id] = $quantity;
    }
  }
}


// Example usage:
// Add an item to the cart
addToCart(123, 2); // Product ID 123, quantity 2
addToCart(456, 1); // Product ID 456, quantity 1

// Display the cart contents
$cartContents = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $quantity) {
    // In a real application, you would fetch the product details
    // based on the product_id.  For this example, we'll just display the ID and quantity.
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
}

// Remove an item from the cart (example)
// removeFromCart(123);

// Update the quantity of an item (example)
// updateQuantity(456, 3);

// Get the updated cart contents (example)
// $cartContents = getCartContents();
// echo "<h2>Updated Cart Contents:</h2>";
// foreach ($cartContents as $product_id => $quantity) {
//   echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
// }

?>
