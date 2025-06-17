

<?php

// Start a session (if not already started)
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Example Functions to Handle Cart Operations ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the product ID exists in the cart
  if (array_key_exists($product_id, $_SESSION['cart'])) {
    // Product already in cart - update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart - add it to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name'     => 'Product Name (Placeholder)' // Add product name (or get it from a database)
    ];
  }
}

/**
 * Remove an item from the cart by product ID.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (array_key_exists($product_id, $_SESSION['cart'])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Update the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function updateQuantity($product_id, $new_quantity) {
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

/**
 * Get the current cart contents.
 *
 * @return array The cart array.
 */
function getCart() {
    return $_SESSION['cart'];
}


// --- Example Usage / Demonstration  ---

// 1. Add some items to the cart:
addToCart(101);    // Add product 101 with quantity 1
addToCart(102, 2);  // Add product 102 with quantity 2
addToCart(101);  // Add another product 101

// 2. Display the cart contents:
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = getCart();
if (count($cart_items) > 0) {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
} else {
    echo "<li>Your cart is empty.</li>";
}
echo "</ul>";


// 3. Remove an item from the cart:
// removeFromCart(102);

// 4. Update a product's quantity:
// updateQuantity(101, 3);

// 5. Display the cart contents after the update
// echo "<h2>Cart Contents after update:</h2>";
// $cart_items = getCart();
// if (count($cart_items) > 0) {
//     foreach ($cart_items as $product_id => $item) {
//         echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
//     }
// } else {
//     echo "<li>Your cart is empty.</li>";
// }
?>
