

<?php

session_start();

// --- Functions to manage the cart ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.  Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] ?? 0;
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Remove an item from the cart.
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
 * Update the quantity of an item in the cart.
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
 * Get the contents of the cart.
 *
 * @return array  An array containing the product IDs and their quantities.
 */
function getCartContents() {
  return $_SESSION['cart'] ?? [];
}


// --- Example Usage (for demonstration) ---

// 1.  Initialization (If the cart doesn't exist, initialize it)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// 2.  Adding items to the cart
addToCart(101, 2); // Add 2 of product ID 101
addToCart(102, 1); // Add 1 of product ID 102
addToCart(101, 3); // Add 3 of product ID 101 (overwriting previous quantity)


// 3. Displaying the cart contents
echo "<h2>Your Cart:</h2>";
echo "<ul>";
$cart_contents = getCartContents();

if (empty($cart_contents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_contents as $product_id => $quantity) {
    //  In a real application, you'd likely fetch product details
    //  based on the $product_id from your database.
    //  For this example, we'll just display the product ID and quantity.
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
}
echo "</ul>";

// 4. Removing an item
removeFromCart(102); // Remove product ID 102

// 5. Updating the quantity
updateCartQuantity(101, 5); // Update the quantity of product ID 101 to 5

// Display the updated cart
echo "<h2>Your Cart (Updated):</h2>";
$cart_contents = getCartContents();

if (empty($cart_contents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_contents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
}
echo "</ul>";


?>
