

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions for Cart Operations ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.  Defaults to 1 if not provided.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 0;  // Initialize if not already in cart
  }
  $_SESSION['cart'][$product_id] += $quantity;
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
 * Gets the contents of the cart.
 *
 * @return array  An array representing the cart contents (product_id => quantity).
 */
function get_cart_contents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int
 */
function get_cart_total_items() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $quantity) {
            $total += $quantity;
        }
    }
    return $total;
}


// --- Example Usage (Simulating a user adding items) ---

// Add item 1 (Product ID 1) to cart with quantity 2
add_to_cart(1, 2);

// Add item 2 (Product ID 2) to cart with quantity 1
add_to_cart(2, 1);

// Update the quantity of item 1 to 5
update_cart_quantity(1, 5);

// Remove item 2 from the cart
remove_from_cart(2);

// Get the contents of the cart
$cart_contents = get_cart_contents();
print_r($cart_contents);

// Get total items in the cart
$total_items = get_cart_total_items();
echo "<br>Total items in cart: " . $total_items . "<br>";

?>
