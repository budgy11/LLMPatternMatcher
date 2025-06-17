

<?php

session_start();

// --- Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id      The ID of the product being added.
 * @param int $quantity       The quantity of the product to add (default: 1).
 * @return void
 */
function add_to_cart(int $product_id, int $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id   The ID of the product to update.
 * @param int $new_quantity The new quantity for the product.
 * @return void
 */
function update_cart_quantity(int $product_id, int $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


/**
 * Removes an item from the cart.
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
 * Gets the contents of the cart.
 *
 * @return array  An array representing the cart contents.
 */
function get_cart_contents() {
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

// --- Example Usage ---

// 1. Add a product to the cart
add_to_cart(123); // Add product with ID 123 to the cart (quantity = 1)
add_to_cart(456, 2); // Add product with ID 456 to the cart (quantity = 2)

// 2. Update the quantity of a product
update_cart_quantity(123, 5); // Increase the quantity of product 123 to 5

// 3. Get the cart contents
$cart_contents = get_cart_contents();
print_r($cart_contents);

// 4. Remove an item
remove_from_cart(456);

// 5. Get the cart contents again
$cart_contents = get_cart_contents();
print_r($cart_contents);

// 6. Clear the cart
clear_cart();

// 7. Get the cart contents after clearing
$cart_contents = get_cart_contents();
print_r($cart_contents); // Should be an empty array
?>
