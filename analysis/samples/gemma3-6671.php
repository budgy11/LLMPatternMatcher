

<?php

// Start a session (if not already started)
session_start();

// --- Cart Management Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id  The ID of the product to add.
 * @param int $quantity   The quantity of the product to add.
 * @return bool True if the item was added successfully, false otherwise.
 */
function addToCart(int $product_id, int $quantity) {
  // Check if the cart already exists.  If not, create it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart.
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already in cart, update the quantity
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    // Product not in cart, add it with the given quantity
    $_SESSION['cart'][$product_id] = $quantity;
  }
  return true;
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity  The new quantity for the product.
 * @return bool True if the update was successful, false otherwise.
 */
function updateCartItem(int $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
        return true;
    }
    return false;
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return bool True if the item was removed successfully, false otherwise.
 */
function removeFromCart(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    return true;
  }
  return false;
}

/**
 * Gets all items in the cart.
 *
 * @return array An array containing the items in the cart.
 */
function getCart() {
  return $_SESSION['cart'];
}


/**
 * Clears the entire cart.
 */
function clearCart() {
  unset($_SESSION['cart']);
}

// --- Example Usage ---

// Add a product to the cart
addToCart(1, 2); // Add 2 of product ID 1 to the cart
addToCart(2, 1); // Add 1 of product ID 2 to the cart
addToCart(1, 3); // Add 3 of product ID 1 to the cart



// Display the cart contents
echo "<h2>Your Cart:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    //  You'd replace this with a database lookup to get the product name and details.
    //  This is just a placeholder.
    $product_name = "Product " . $product_id;
    echo "<li>$product_name: " . $quantity . "</li>";
  }
  echo "</ul>";
}


// Update the quantity of a product
updateCartItem(1, 5);

// Display the updated cart contents
echo "<h2>Your Cart (Updated):</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    //  You'd replace this with a database lookup to get the product name and details.
    //  This is just a placeholder.
    $product_name = "Product " . $product_id;
    echo "<li>$product_name: " . $quantity . "</li>";
  }
  echo "</ul>";
}


// Remove an item from the cart
removeFromCart(2);

// Display the cart after removal
echo "<h2>Your Cart (After Removal):</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    //  You'd replace this with a database lookup to get the product name and details.
    //  This is just a placeholder.
    $product_name = "Product " . $product_id;
    echo "<li>$product_name: " . $quantity . "</li>";
  }
  echo "</ul>";
}



// Clear the cart (optional)
// clearCart();
?>
