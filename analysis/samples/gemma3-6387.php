

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --------------------- Cart Functions ---------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add (default: 1).
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
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}


/**
 * Gets all items in the cart.
 *
 * @return array An array containing all items in the cart.
 */
function get_cart_items() {
  return $_SESSION['cart'];
}

/**
 * Gets the total number of items in the cart.
 *
 * @return int
 */
function get_cart_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Assuming you have a way to get the price of the product
    // Replace this with your actual product price retrieval logic.
    $price = get_product_price($product_id); // Replace with your function
    $total_for_item = $price * $quantity;
    $total = $total + $total_for_item;
  }
  return $total;
}


// --------------------- Example Usage (Demonstration) ---------------------

// Add some items to the cart
add_to_cart(1, 2); // Product ID 1, quantity 2
add_to_cart(2, 1); // Product ID 2, quantity 1
add_to_cart(1, 3); // Product ID 1, quantity 3

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = get_cart_items();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";

// Display the total
echo "<p>Total: " . get_cart_total() . "</p>";

// Remove an item
remove_from_cart(2);

// Display updated cart contents
echo "<br><h2>Cart Contents (after removal):</h2>";
$cart_items = get_cart_items();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";

// Update quantity
update_cart_quantity(1, 5);

// Display updated cart contents
echo "<br><h2>Cart Contents (after update):</h2>";
$cart_items = get_cart_items();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";

// Display the total
echo "<p>Total: " . get_cart_total() . "</p>";



?>
