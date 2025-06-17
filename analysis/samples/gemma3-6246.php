

<?php

// Start a session if it's not already started
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    // If it doesn't, initialize it as an empty array
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add a new item to the cart
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

/**
 * Removes an item from the cart.
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


/**
 * Gets all items in the cart.
 *
 * @return array  An array containing all items in the cart, or an empty array if the cart is empty.
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



// --- Example Usage (Demonstration) ---

// Add some items to the cart
addToCart(101); // Product ID 101
addToCart(102, 2); // Product ID 102, quantity 2
addToCart(101, 3); // Product ID 101, quantity 3

// Display the contents of the cart
echo "<h2>Cart Items:</h2>";
echo "<ul>";
$cart_items = getCartItems();
if (empty($cart_items)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_items as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
}
echo "</ul>";

// Update the quantity of a product
updateCartQuantity(101, 5); // Change quantity of product 101 to 5

// Display the cart again after updating
echo "<br><h2>Cart Items (Updated):</h2>";
$cart_items = getCartItems();
if (empty($cart_items)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_items as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
}
echo "</ul>";


// Remove a product from the cart
removeFromCart(102);

// Display the cart after removing an item
echo "<br><h2>Cart Items (After Removal):</h2>";
$cart_items = getCartItems();
if (empty($cart_items)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_items as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
}
echo "</ul>";


// Clear the cart
//clearCart();

// Display the cart after clearing
// echo "<br><h2>Cart Items (After Clearing):</h2>";
// $cart_items = getCartItems();
// if (empty($cart_items)) {
//   echo "<li>Cart is empty.</li>";
// } else {
//   foreach ($cart_items as $product_id => $item) {
//     echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
//   }
// }
// echo "</ul>";
?>
