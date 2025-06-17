

<?php
session_start();

// --- Cart Management Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Removes an item from the cart by product ID.
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
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array  An array representing the cart contents (product_id => quantity).
 */
function getCart() {
  return $_SESSION['cart'] ?? []; // Use null coalesce operator for empty cart
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int
 */
function getCartTotal() {
  $total = 0;
  foreach (getCart() as $quantity) {
    $total += $quantity;
  }
  return $total;
}


// --- Example Usage ---

// 1. Adding an item to the cart
addToCart(123); // Add product 123 to the cart (quantity defaults to 1)
addToCart(456, 2); // Add product 456 to the cart with quantity 2

// 2.  Displaying the cart contents
echo "<h2>Your Cart</h2>";
if (empty(getCart())) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach (getCart() as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Items in Cart: " . getCartTotal() . "</p>";
}


// 3. Removing an item from the cart
removeFromCart(456);

// 4. Updating the quantity of an item
updateCartQuantity(123, 3); // Change quantity of product 123 to 3

// 5. Displaying the cart contents after updates
echo "<h2>Your Cart (After Updates)</h2>";
if (empty(getCart())) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach (getCart() as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Items in Cart: " . getCartTotal() . "</p>";
}


?>
