

<?php

// Define the session name (used to identify the session)
$session_name = 'shopping_cart';

// Start the session if it doesn't exist
session_name($session_name);
session_start();

// ---  Functions for Cart Management ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart(int $product_id, int $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = $quantity;
  }
}


/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Gets all items in the cart.
 *
 * @return array An array containing all items in the cart.
 */
function getCart() {
  return $_SESSION['cart'] ?? []; // Use null coalescing operator for default empty array
}


/**
 * Gets the total number of items in the cart.
 *
 * @return int
 */
function cartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
      // Assume price of $10 per product for demonstration.  Replace with your actual price
      $total_for_product = 10;
      $total_for_product += $total_for_product * $quantity;
      $total = $total + $total_for_product * $quantity;
    }
  }
  return $total;
}



// --- Example Usage ---

// Add a product to the cart
addToCart(123); // Add product ID 123
addToCart(456, 2); // Add product ID 456 with quantity 2

// Display the contents of the cart
$cart_contents = getCart();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
}

// Display the total
$total = cartTotal();
echo "<p>Total: $" . $total . "</p>";


// Remove a product from the cart
removeFromCart(123);

// Display the updated cart contents
$cart_contents = getCart();
echo "<h2>Cart Contents after removing product 123:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
}
?>
