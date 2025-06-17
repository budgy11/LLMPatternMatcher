

<?php
session_start();

// ------------------ Session Variables ------------------

// Initialize session variables if they don't exist.
// This is important for cart management.

$_SESSION['cart'] = array(); // Initialize an empty cart array.
$_SESSION['cart_total'] = 0;  // Initialize the total cart value to 0

// ------------------ Helper Functions ------------------

/**
 * Adds an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
  global $_SESSION['cart'];
  global $_SESSION['cart_total'];

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already in cart, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it with the given quantity
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity, 'price' => 0); //  Price is set to 0.  You'd likely fetch this from a database.
  }

  // Update the total cart value
  $_SESSION['cart_total'] = 0;
  foreach ($_SESSION['cart'] as $item) {
    $_SESSION['cart_total'] += $item['quantity'] * $item['price']; // Assumes $item['price'] is a database value
  }
}

/**
 * Removes an item from the shopping cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  global $_SESSION['cart'];

  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Clears the entire shopping cart.
 *
 * @return void
 */
function clearCart() {
  global $_SESSION['cart'];
  global $_SESSION['cart_total'];

  $_SESSION['cart'] = array();
  $_SESSION['cart_total'] = 0;
}

/**
 * Returns the contents of the shopping cart.
 *
 * @return array The shopping cart contents.
 */
function getCartContents() {
    return $_SESSION['cart'];
}

// ------------------ Example Usage (Illustrative) ------------------

// 1. Add a product to the cart
addToCart(123, 2);  // Add product with ID 123, quantity 2

// 2. Add another product
addToCart(456, 1);

// 3. Remove a product
removeFromCart(123);

// 4. Get the cart contents
$cart_contents = getCartContents();
print_r($cart_contents); // Display the cart contents
echo "<br>";

// 5. Clear the cart
//clearCart();
//echo "<br>Cart cleared.";

// ------------------  Output (After executing the example) ------------------
// You'll see something like this, depending on the items in the cart:
// Array
// (
//     [456] => Array
//         (
//             [quantity] => 1
//             [price] => 0
//         )
// )
?>
