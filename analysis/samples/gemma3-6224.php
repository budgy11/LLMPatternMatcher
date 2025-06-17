

<?php
session_start();

// ----------------------- Cart Functions -----------------------

/**
 * Adds an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity  The quantity to add. Defaults to 1.
 * @return void
 */
function addToCart($productId, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0; // Initialize if not present
    $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + $quantity;
  } else {
    $_SESSION['cart'][$productId] = $quantity;
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $productId The ID of the product to update.
 * @param int $quantity  The new quantity.
 * @return void
 */
function updateCartQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return void
 */
function removeFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

/**
 * Gets the cart items.
 *
 * @return array The cart items as an array.
 */
function getCartItems() {
  return $_SESSION['cart'];
}

/**
 * Gets the total cart value.
 *
 * @return float The total cart value.  Returns 0 if the cart is empty.
 */
function getCartTotal() {
  $total = 0.0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
      // Assuming you have a function to get the price of a product based on its ID
      $price = getProductPrice($productId); //  Implement this function
      if ($price > 0) { // Prevent division by zero
        $totalQuantity = $quantity; // Use the quantity directly here
        $total += $price * $totalQuantity;
      }
    }
  }
  return $total;
}


// ----------------------- Example Usage -----------------------

// 1. Add an item to the cart
addToCart(123, 2); // Add product ID 123 to the cart with quantity 2

// 2. Update the quantity of an item
updateCartQuantity(123, 5); // Increase the quantity of product 123 to 5

// 3. Remove an item from the cart
// removeFromCart(123);

// 4. Get the cart items
$cartItems = getCartItems();
print_r($cartItems);

// 5. Get the total cart value (assuming product prices are known)
$total = getCartTotal();
echo "Total cart value: $" . number_format($total, 2) . "
";

?>
