

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Example Functions for Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}


/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity  The new quantity of the product.
 * @return void
 */
function update_cart_item($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}


/**
 * Gets the total number of items in the cart.
 *
 * @return int
 */
function get_cart_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $quantity) {
    $total += $quantity;
  }
  return $total;
}


/**
 * Gets the contents of the cart.
 *
 * @return array  An array containing the cart's contents
 */
function get_cart_contents() {
  return $_SESSION['cart'];
}

// --- Example Usage / Demonstration ---

// 1. Adding items to the cart
add_to_cart(101, 2); // Add 2 units of product with ID 101
add_to_cart(102, 1); // Add 1 unit of product with ID 102
add_to_cart(101, 3); // Add 3 units of product with ID 101
add_to_cart(103, 1); // Add 1 unit of product with ID 103

// 2. Displaying the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Items in Cart: " . get_cart_total() . "</p>";


// 3. Removing an item
remove_from_cart(102);

// 4. Updating an item
update_cart_item(101, 5);

echo "<p>Total Items in Cart: " . get_cart_total() . "</p>";

// 5. Displaying cart contents again after changes
echo "<p>Total Items in Cart: " . get_cart_total() . "</p>";

//Display updated cart contents
echo "<p>Total Items in Cart: " . get_cart_total() . "</p>";

echo "<p>Updated Cart Contents: " . print_r($_SESSION['cart'], true) . "</p>";
?>
