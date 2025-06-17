

<?php
session_start(); // Start the session

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions to handle cart operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes a specific item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of a specific item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    // If product is not in cart, consider it as a new item with the specified quantity
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array An array representing the cart contents.
 */
function getCartContents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int
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


// --- Example Usage (Demonstration - can be removed when integrating with your application) ---

// 1. Add a product to the cart
addToCart(123, 2); // Add 2 of product with ID 123
addToCart(456, 1);  // Add 1 of product with ID 456

// 2. Display the cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Items in Cart: " . getCartTotal() . "</p>";

// 3.  Update the quantity of an item
updateQuantity(123, 3);

echo "<p>Updated Cart Contents:</p>";
echo "<ul>";
foreach ($_SESSION['cart'] as $product_id => $quantity) {
  echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";

// 4. Remove an item
removeFromCart(456);

echo "<p>Cart Contents After Removal:</p>";
echo "<ul>";
foreach ($_SESSION['cart'] as $product_id => $quantity) {
  echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";
?>
