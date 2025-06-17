

<?php
session_start();

// --- Functions to Handle Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id  The ID of the product to add.
 * @param int    $quantity   The quantity to add. Defaults to 1.
 * @return void
 */
function add_to_cart(string $product_id, int $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $quantity  The new quantity.
 * @return void
 */
function update_cart_quantity(string $product_id, int $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(string $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Returns the contents of the cart.
 *
 * @return array The cart contents.
 */
function get_cart_contents() {
  return $_SESSION['cart'] ?? []; // Return an empty array if cart is empty.
}


// --- Example Usage (Demonstration) ---

// 1. Initializing the Cart (if it doesn't exist)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// 2. Adding Items to the Cart
add_to_cart('product1', 2); // Add 2 of product1
add_to_cart('product2', 1); // Add 1 of product2
add_to_cart('product1', 3); // Add 3 more of product1

// 3. Updating the quantity of an item
update_cart_quantity('product2', 5);

// 4. Removing an item
remove_from_cart('product1');


// 5. Displaying the Cart Contents
echo "<h2>Your Cart:</h2>";
$cart_contents = get_cart_contents();

if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

?>
