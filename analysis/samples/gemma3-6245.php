

<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return bool True if the item was added, false otherwise (e.g., invalid product ID).
 */
function addToCart(int $product_id, int $quantity) {
  // Validate product ID (you should have better validation in a real application)
  if (!is_numeric($product_id) || $product_id <= 0) {
    return false;
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the item to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'price'    => 0 // Replace with actual product price (from database)
    ];
  }
  return true;
}


/**
 * Retrieves the contents of the cart.
 *
 * @return array The cart contents as an associative array.
 */
function getCart() {
  return $_SESSION['cart'] ?? []; // Return an empty array if cart is not initialized
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity for the product.
 * @return bool True if the quantity was updated, false otherwise.
 */
function updateCartQuantity(int $product_id, int $quantity) {
    if (!is_numeric($product_id) || $product_id <= 0) {
        return false;
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }

    return true;
}



/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return bool True if the item was removed, false otherwise.
 */
function removeFromCart(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    return true;
  }
  return false;
}



// --- Example Usage (Demonstration) ---

// 1. Add some items to the cart
addToCart(1, 2);  // Add product ID 1 in quantity 2
addToCart(2, 1);  // Add product ID 2 in quantity 1
addToCart(1, 3);  // Add product ID 1 in quantity 3 (updates existing quantity)

// Display the contents of the cart
$cart = getCart();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: $product_id, Quantity: $item['quantity']</li>";
  }
  echo "</ul>";
}


// 2.  Update the quantity of a product
updateCartQuantity(1, 5); // Change quantity of product 1 to 5

// Display the cart after update
$cart = getCart();
echo "<h2>Cart Contents (After Update):</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: $product_id, Quantity: $item['quantity']</li>";
  }
  echo "</ul>";
}

// 3. Remove an item from the cart
removeFromCart(2);

// 4. Display the cart after removal
$cart = getCart();
echo "<h2>Cart Contents (After Removal):</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: $product_id, Quantity: $item['quantity']</li>";
  }
  echo "</ul>";
}

?>
