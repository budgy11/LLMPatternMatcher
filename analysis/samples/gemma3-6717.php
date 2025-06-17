

<?php
session_start();

// Function to add items to the cart
function addToCart($product_id, $quantity) {
  // Check if the cart already exists in the session
  if (isset($_SESSION['cart']) === false) {
    // If not, initialize an empty cart array
    $_SESSION['cart'] = array();
  }

  // Get the existing cart items
  $cartItems = $_SESSION['cart'];

  // Check if the product is already in the cart
  if (isset($cartItems[$product_id])) {
    // If it exists, increment the quantity
    $cartItems[$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart with quantity 1
    $cartItems[$product_id] = array('quantity' => $quantity);
  }

  // Update the cart in the session
  $_SESSION['cart'] = $cartItems;
}

// Function to update the quantity of a product in the cart
function updateQuantity($product_id, $quantity) {
    // Check if the cart exists
    if (isset($_SESSION['cart']) === false) {
        return false; // Cart doesn't exist, can't update
    }

    $cartItems = $_SESSION['cart'];

    if (isset($cartItems[$product_id])) {
        // Update the quantity
        $cartItems[$product_id]['quantity'] = $quantity;
        $_SESSION['cart'] = $cartItems;
        return true;
    } else {
        return false; // Product not found in cart
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  // Check if the cart exists
  if (isset($_SESSION['cart']) === false) {
    return false; // Cart doesn't exist, can't remove
  }

  $cartItems = $_SESSION['cart'];

  if (isset($cartItems[$product_id])) {
    unset($cartItems[$product_id]);
    $_SESSION['cart'] = $cartItems;
    return true;
  } else {
    return false; // Product not found in cart
  }
}

// Function to get the cart items
function getCartItems() {
  if (isset($_SESSION['cart']) === false) {
    return array();
  }

  return $_SESSION['cart'];
}

// Example Usage:

// 1. Add an item to the cart
// Suppose $product_id = 123 and $quantity = 2
// addToCart(123, 2);

// 2. Update the quantity of a product (e.g., increase the quantity of product 123 by 1)
// updateQuantity(123, 1);

// 3. Remove an item from the cart
// removeCartItem(123);

// 4. Get the current cart items
$cart = getCartItems();
echo "Cart Items: ";
print_r($cart);

// To display the cart items on a webpage (example):
// You'd typically loop through the $cart array and display the product name, image, quantity, and price.

?>
