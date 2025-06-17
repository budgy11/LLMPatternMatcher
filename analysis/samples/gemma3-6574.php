

<?php

session_start();

// --------------------------------------------------
// Cart Functions
// --------------------------------------------------

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.  Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the cart exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product is already in cart, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it with quantity 1
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

/**
 * Removes a product from the cart.
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
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


/**
 * Gets all items in the cart.
 *
 * @return array|null  Returns the cart array if it exists, otherwise null.
 */
function getCart() {
  return $_SESSION['cart'] ?? null;
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
// Example Usage / Demonstration
// --------------------------------------------------

// 1. Add an item to the cart
addToCart(123); // Adds product with ID 123 to the cart, quantity 1
addToCart(456, 2); // Adds product with ID 456 to the cart, quantity 2


// 2. Display the cart contents
$cart = getCart();

if ($cart) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


// 3.  Example of updating quantity
updateQuantity(123, 5);

// Display the updated cart
$cart = getCart();

if ($cart) {
    echo "<h2>Your Shopping Cart (Updated)</h2>";
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// 4. Removing an item
removeFromCart(456);

// Display the cart again
$cart = getCart();

if ($cart) {
    echo "<h2>Your Shopping Cart (After Removal)</h2>";
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// 5. Clearing the cart
clearCart();
echo "<br><h2>Cart after clearing</h2>";
$cart = getCart();
if ($cart) {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}


?>
