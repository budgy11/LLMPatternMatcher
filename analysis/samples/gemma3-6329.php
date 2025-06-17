

<?php

session_start();

// --------------------------------------------------
//  Functions to handle cart operations
// --------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($productId, $quantity) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If it's not, add it to the cart with a quantity of $quantity
    $_SESSION['cart'][$productId] = array('quantity' => $quantity);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $productId The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($productId, $quantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return void
 */
function removeFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array  An array containing all items in the cart.
 */
function getCartItems() {
  return $_SESSION['cart'];
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
  unset($_SESSION['cart']);
}

// --------------------------------------------------
//  Example Usage / Demonstration
// --------------------------------------------------

// Add some items to the cart
addToCart(1, 2);  // Add 2 of product ID 1
addToCart(2, 1);  // Add 1 of product ID 2
addToCart(1, 3);  // Add 3 of product ID 1

// Print the contents of the cart
echo "<h2>Cart Items:</h2>";
echo "<ul>";
$cartItems = getCartItems();
if (empty($cartItems)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cartItems as $productId => $item) {
    echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
  }
}
echo "</ul>";

// Update the quantity of product ID 1 to 5
updateCartQuantity(1, 5);

// Print the updated cart
echo "<br><h2>Updated Cart Items:</h2>";
$cartItems = getCartItems();
echo "<ul>";
foreach ($cartItems as $productId => $item) {
  echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
}
echo "</ul>";

// Remove product ID 2 from the cart
removeFromCart(2);

// Print the updated cart again
echo "<br><h2>Cart Items after Removal:</h2>";
$cartItems = getCartItems();
echo "<ul>";
foreach ($cartItems as $productId => $item) {
  echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
}
echo "</ul>";


// Clear the cart
clearCart();

echo "<br><h2>Cart Items after Clearing:</h2>";
$cartItems = getCartItems();
if (empty($cartItems)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cartItems as $productId => $item) {
    echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
  }
}
echo "</ul>";
?>
