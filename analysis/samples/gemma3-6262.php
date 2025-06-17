

<?php
session_start();

// ------------------- Session Management Functions -------------------

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart(int $product_id, int $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart with a quantity of 1
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity for the product.
 * @return void
 */
function update_cart_quantity(int $product_id, int $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents as an array.
 */
function get_cart() {
  return $_SESSION['cart'] ?? []; // Returns the cart or an empty array if not initialized.
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clear_cart() {
  unset($_SESSION['cart']);
}

// ------------------- Example Usage (This is for demonstration) -------------------

// 1. Add a product to the cart
add_to_cart(123); // Adds product ID 123 to the cart with a quantity of 1.
add_to_cart(456, 2); // Adds product ID 456 to the cart with a quantity of 2.


// 2. Get the current cart contents
$cart_items = get_cart();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// 3. Update the quantity of product 123 to 3
update_cart_quantity(123, 3);

// 4.  Display cart after update
echo "<h2>Cart Contents After Update:</h2>";
$cart_items = get_cart();
echo "<ul>";
foreach ($cart_items as $product_id => $item) {
  echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
}
echo "</ul>";


// 5. Remove product 456
remove_from_cart(456);

// 6. Display cart after removal
echo "<h2>Cart Contents After Removal:</h2>";
$cart_items = get_cart();
echo "<ul>";
foreach ($cart_items as $product_id => $item) {
  echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
}
echo "</ul>";


// 7. Clear the cart
clear_cart();
echo "<h2>Cart Contents After Clearing:</h2>";
$cart_items = get_cart();
if (empty($cart_items)) {
  echo "<p>Your cart is now empty.</p>";
} else {
  echo "<p>Your cart is not empty.  This shouldn't happen.</p>"; // Debugging
}
?>
