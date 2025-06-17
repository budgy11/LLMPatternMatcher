

<?php
session_start(); // Start the session

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --------------------- Functions ---------------------

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add (defaults to 1).
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
 * Removes a product from the cart.
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
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
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
 * @return array  An array of product IDs and quantities.
 */
function get_cart() {
  return $_SESSION['cart'];
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clear_cart() {
  unset($_SESSION['cart']);
}

// --------------------- Example Usage (for demonstration) ---------------------

// Add a product to the cart
add_to_cart(123); // Add product with ID 123 in quantity 1
add_to_cart(456, 2); // Add product with ID 456 in quantity 2

// Update the quantity of product 123 to 3
update_cart_quantity(123, 3);


// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = get_cart();
if (empty($cart_items)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
}
echo "</ul>";


// Example: Remove a product
// remove_from_cart(456);

// Example: Clear the entire cart
// clear_cart();

// After clearing, the cart contents will be empty
?>
