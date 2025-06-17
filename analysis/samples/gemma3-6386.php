

<?php
session_start(); // Start the session

// --- Cart Functions ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
  // Check if the cart already exists.  If not, create it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    // Otherwise, add the product to the cart
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Get the total number of items in the cart.
 *
 * @return int The total number of items in the cart.
 */
function getCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity => $count) {
      $total = $total + $count;
    }
  }
  return $total;
}

/**
 * Remove a product from the cart by its ID.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Get the quantity of a specific product in the cart.
 *
 * @param int $product_id The ID of the product to retrieve the quantity for.
 * @return int The quantity of the product in the cart, or 0 if not found.
 */
function getCartQuantity($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        return $_SESSION['cart'][$product_id];
    }
    return 0;
}

/**
 * Clear the entire cart.
 *
 * @return void
 */
function clearCart() {
    unset($_SESSION['cart']);
}

// --- Example Usage (Illustrative) ---

// Add some items to the cart
addToCart(1, 2);  // Product ID 1, quantity 2
addToCart(2, 1);  // Product ID 2, quantity 1
addToCart(1, 3);  // Product ID 1, quantity 3 (increases existing quantity)

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    echo "<li>Product ID: $product_id, Quantity: $quantity</li>";
  }
  echo "</ul>";
}

echo "<p>Total Items in Cart: " . getCartTotal() . "</p>";

// Remove a product
removeFromCart(2);

// Display the updated cart contents
echo "<p>Updated Cart Contents: </p>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    echo "<li>Product ID: $product_id, Quantity: $quantity</li>";
  }
  echo "</ul>";
}

// Clear the cart
//clearCart();  // Uncomment to clear the cart

// Example: Get the quantity of a specific product
$product_id_to_check = 1;
$quantity_of_product = getCartQuantity($product_id_to_check);
echo "<p>Quantity of Product ID $product_id_to_check in the cart: $quantity_of_product</p>";
?>
