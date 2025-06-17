

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions for Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.  Defaults to 1 if not provided.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 0; // Initialize count if not already in cart
  }
  $_SESSION['cart'][$product_id] += $quantity;
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
 * @param int $new_quantity The new quantity for the product.
 * @return void
 */
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $new_quantity;
  }
}

/**
 * Gets the items in the cart.
 *
 * @return array An array representing the cart contents.
 */
function get_cart_contents() {
  return $_SESSION['cart'];
}

/**
 *  Calculates the total number of items in the cart
 *
 * @return int
 */
function cart_total() {
    $total = 0;
    foreach($_SESSION['cart'] as $quantity) {
        $total += $quantity;
    }
    return $total;
}


// --- Example Usage (Demonstration) ---

// 1. Add an item to the cart
add_to_cart(123, 2); // Add 2 of product ID 123
add_to_cart(456, 1); // Add 1 of product ID 456

// 2. Update the quantity of an item
update_cart_quantity(123, 5);  // Change quantity of product 123 to 5

// 3. Remove an item from the cart
remove_from_cart(456);


// 4. Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = get_cart_contents();
if (empty($cart_items)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
}
echo "</ul>";

echo "<p>Total Items in Cart: " . cart_total() . "</p>";



//  (Optional) Clear the cart (for testing or resetting)
//  session_unset();
//  session_destroy();
?>
