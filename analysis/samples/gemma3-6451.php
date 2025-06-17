

<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 */
function updateCartItem($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $new_quantity;
  }
}

/**
 * Gets the items in the cart.
 *
 * @return array An array of cart items.
 */
function getCartItems() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int The total number of items in the cart.
 */
function getCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity => $product_id) {
      $total += $quantity;
    }
  }
  return $total;
}

/**
 * Clears the entire cart.
 */
function clearCart() {
  unset($_SESSION['cart']);
}


// --- Example Usage (Illustrative) ---

// Add a product to the cart
addToCart(123); // Add product ID 123 in quantity 1
addToCart(456, 2); // Add product ID 456 in quantity 2

// Display the cart contents
echo "<h2>Cart Items:</h2>";
if (count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

echo "<p>Total Items in Cart: " . getCartTotal() . "</p>";

// Example: Remove a product
//removeCartItem(123);

// Example: Update quantity
//updateCartItem(456, 3);

// Example: Clear the cart
//clearCart();


?>
