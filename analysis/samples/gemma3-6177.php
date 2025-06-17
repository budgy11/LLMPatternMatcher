

<?php
session_start();

// ------------------  Session Management Functions ------------------

/**
 * Adds an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity to add.
 * @return void
 */
function addToCart(int $productId, int $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    // Item already in cart, update quantity
    $_SESSION['cart'][] = $productId;
  } else {
    // Item not in cart, add it
    $_SESSION['cart'] = [$productId => $quantity]; // Use associative array for quantity
  }
}

/**
 * Removes an item from the cart by its product ID.
 *
 * @param int $productId The ID of the product to remove.
 * @return void
 */
function removeFromCart(int $productId) {
  if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart'][$productId]);
    // Remove duplicate keys
    $_SESSION['cart'] = array_values($_SESSION['cart']);
  }
}

/**
 * Retrieves the items in the cart.
 *
 * @return array|null Returns the cart items as an array, or null if the cart is empty.
 */
function getCart() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return null;
}


/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
  unset($_SESSION['cart']);
}


// ------------------  Example Usage (For Demonstration) ------------------

// --- Example 1: Adding an item ---
addToCart(123); // Add product ID 123 to the cart (quantity defaults to 1)
addToCart(456, 3); // Add product ID 456 to the cart with quantity 3

// --- Example 2:  Retrieving the cart contents ---
$cartItems = getCart();
if ($cartItems) {
  echo "<h2>Cart Items:</h2>";
  echo "<ul>";
  foreach ($cartItems as $productId => $quantity) {
    echo "<li>Product ID: " . $productId . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}



// --- Example 3: Removing an item ---
removeFromCart(123);

// --- Example 4:  Retrieving the cart contents after removal ---
$cartItems = getCart();
if ($cartItems) {
  echo "<h2>Cart Items after removal:</h2>";
  echo "<ul>";
  foreach ($cartItems as $productId => $quantity) {
    echo "<li>Product ID: " . $productId . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


// --- Example 5: Clearing the cart ---
//clearCart();
//echo "<p>Cart cleared.</p>";
?>
