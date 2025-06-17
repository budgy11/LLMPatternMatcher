

<?php
session_start(); // Start the session

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int $quantity The quantity of the product to add.
 * @param float $price The price of the product per unit.
 * @return bool True if the item was added, false otherwise (e.g., invalid quantity).
 */
function addToCart(string $product_id, string $name, int $quantity, float $price) {
  // Validation
  if ($quantity <= 0) {
    return false;
  }

  // If the item is already in the cart, update the quantity.
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If not, add a new item to the cart.
    $_SESSION['cart'][$product_id] = [
      'name' => $name,
      'quantity' => $quantity,
      'price' => $price
    ];
  }
  return true;
}


/**
 * Retrieves the items in the cart.
 *
 * @return array An array representing the cart contents.
 */
function getCart() {
  return $_SESSION['cart'] ?? []; // Use ?? to return an empty array if 'cart' is not set
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 * @return bool True if the quantity was updated, false otherwise (e.g., invalid quantity).
 */
function updateCartItemQuantity(string $product_id, int $new_quantity) {
    if ($new_quantity <= 0) {
        return false;
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
    return true;
}



/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart(string $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
  unset($_SESSION['cart']);
}


/**
 * Calculates the total cost of the items in the cart.
 *
 * @return float The total cost.
 */
function calculateCartTotal() {
  $total = 0;
  $cart = getCart();
  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}



// --- Example Usage (for demonstration) ---

// Add some items to the cart
addToCart('product1', 'Awesome T-Shirt', 2, 25.00);
addToCart('product2', 'Cool Hat', 1, 15.00);


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
$cart = getCart();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
  echo "<p>Total: $" . calculateCartTotal() . "</p>";
}

// Example: Update the quantity of a product
updateCartItemQuantity('product1', 3);

// Example: Remove an item from the cart
removeFromCart('product2');


// Display the cart contents after changes
echo "<h2>Your Shopping Cart (Updated)</h2>";
$cart = getCart();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
  echo "<p>Total: $" . calculateCartTotal() . "</p>";
}

// Example: Clear the cart
// clearCart();

// Display the cart contents after clearing
// echo "<h2>Your Shopping Cart (Cleared)</h2>";
// $cart = getCart();
// if (empty($cart)) {
//   echo "<p>Your cart is empty.</p>";
// }
?>
