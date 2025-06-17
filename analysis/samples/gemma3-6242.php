

<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The unique ID of the product.
 * @param string $name      The name of the product.
 * @param int    $quantity  The quantity of the product to add.
 * @param float  $price     The price per item.
 *
 * @return void
 */
function addToCart(string $product_id, string $name, int $quantity, float $price) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][] = [
    'product_id' => $product_id,
    'name'       => $name,
    'quantity'   => $quantity,
    'price'      => $price,
  ];
}

/**
 * Updates the quantity of an existing item in the cart.
 *
 * @param string $product_id The unique ID of the product.
 * @param int    $newQuantity The new quantity of the item.
 *
 * @return void
 */
function updateCartItem(string $product_id, int $newQuantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
  }
}

/**
 * Removes an item from the cart by its ID.
 *
 * @param string $product_id The unique ID of the product.
 *
 * @return void
 */
function removeCartItem(string $product_id) {
  unset($_SESSION['cart'][$product_id]);
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateCartTotal() {
  $total = 0.0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['quantity'] * $item['price'];
    }
  }
  return $total;
}


/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
  unset($_SESSION['cart']);
}

// --- Example Usage (Demonstration) ---

// 1. Add an item to the cart
addToCart('product123', 'T-Shirt', 2, 20.00);
addToCart('product456', 'Jeans', 1, 50.00);


// 2. Update the quantity of an item
updateCartItem('product123', 3); // Increase the quantity of T-Shirt to 3

// 3. Remove an item
// removeCartItem('product456');

// 4. Calculate the total
$total = calculateCartTotal();
echo "Cart Total: $" . number_format($total, 2) . "<br>";

// 5. Clear the cart
// clearCart();
// echo "Cart cleared.";

// --- Display Cart Contents (For demonstration only - adapt to your website layout) ---
echo "<br>";
echo "<h2>Cart Contents:</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . number_format($item['price'], 2) . "</li>";
  }
  echo "</ul>";
}

?>
