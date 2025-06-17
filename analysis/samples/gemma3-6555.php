

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --------------------------------------------------
// Helper Functions
// --------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes an item from the cart by product ID.
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}


/**
 * Gets all items in the cart.
 *
 * @return array An array representing the cart contents.
 */
function getCartContents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total price of the cart.
 *
 * @param array $cartItems An array of item details (product_id => price).
 * @return float The total price.
 */
function calculateTotalPrice($cartItems) {
  $total = 0;
  foreach ($cartItems as $product_id => $quantity) {
    // Assuming you have a product database or data source to get the price
    $price = getProductPrice($product_id); // Replace with your price retrieval method
    $totalPriceForProduct = $price * $quantity;
    $total += $totalPriceForProduct;
  }
  return $total;
}

// --------------------------------------------------
// Example Usage (Simulated)
// --------------------------------------------------

// Example: Add an item to the cart
addToCart(123, 2); // Add 2 of product ID 123
addToCart(456, 1); // Add 1 of product ID 456

// Example: Update the quantity of an item
updateCartQuantity(123, 5);

// Example: Remove an item from the cart
removeFromCart(456);

// Get the cart contents
$cart = getCartContents();
echo "Cart Contents: <br>";
print_r($cart);

// Calculate the total price
$productPrices = [
  123 => 10.00,  //Price for product 123
  456 => 25.00   //Price for product 456
];
$total = calculateTotalPrice($productPrices, $cart);
echo "Total Price: $" . $total . "<br>";
?>
