

<?php
session_start();

// ----------------------- Cart Functions -----------------------

/**
 * Adds an item to the cart.
 *
 * @param string $productId The ID of the product being added.
 * @param int    $quantity  The quantity of the product to add.
 * @return void
 */
function add_to_cart(string $productId, int $quantity = 1)
{
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart if it doesn't exist
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If it's already in the cart, increase the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If it's not in the cart, add it with the given quantity
    $_SESSION['cart'][$productId] = ['quantity' => $quantity];
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $productId The ID of the product to update.
 * @param int    $quantity  The new quantity of the product.
 * @return void
 */
function update_cart_quantity(string $productId, int $quantity)
{
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param string $productId The ID of the product to remove.
 * @return void
 */
function remove_from_cart(string $productId)
{
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}


/**
 * Gets all items in the cart.
 *
 * @return array  An array of product items in the cart.
 */
function get_cart_items()
{
  return $_SESSION['cart'] ?? []; // Return an empty array if cart is not initialized
}


/**
 * Clears the entire cart.
 *
 * @return void
 */
function clear_cart()
{
  unset($_SESSION['cart']);
}


// ----------------------- Example Usage (Simulated) -----------------------

// Example: Add some products to the cart
add_to_cart('product1', 2);
add_to_cart('product2', 1);
add_to_cart('product1', 1); // Adding more of product1

// Display the cart contents
echo "<h2>Cart Items:</h2>";
$cart_items = get_cart_items();

if (count($cart_items) > 0) {
  echo "<ul>";
  foreach ($cart_items as $productId => $item) {
    echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


// Example: Update the quantity of product1 to 3
update_cart_quantity('product1', 3);

// Display the updated cart
echo "<h2>Updated Cart Items:</h2>";
$cart_items = get_cart_items();

if (count($cart_items) > 0) {
  echo "<ul>";
  foreach ($cart_items as $productId => $item) {
    echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

// Example: Remove product2 from the cart
remove_from_cart('product2');

// Example: Clear the cart
// clear_cart();
?>
