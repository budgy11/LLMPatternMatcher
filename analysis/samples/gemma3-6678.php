

<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id      The ID of the product to add.
 * @param string $product_name    The name of the product.
 * @param int    $quantity       The quantity to add (default: 1).
 * @param float  $price          The price of the product.
 *
 * @return bool True on success, false on failure (e.g., invalid quantity).
 */
function addToCart(string $product_id, string $product_name, int $quantity = 1, float $price) {
  // Validate quantity
  $quantity = max(1, (int)$quantity); // Ensure quantity is at least 1

  if ($quantity <= 0) {
    return false;
  }

  // Check if the cart exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add new product to cart
    $_SESSION['cart'][$product_id] = [
      'name'    => $product_name,
      'quantity' => $quantity,
      'price'   => $price
    ];
  }

  return true;
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $quantity  The new quantity for the product.
 *
 * @return bool True on success, false on failure (e.g., product not found).
 */
function updateCartItem(string $product_id, int $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        return false;
    }

    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    return true;
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 *
 * @return bool True on success, false if the product is not in the cart.
 */
function removeFromCart(string $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    return true;
  }
  return false;
}

/**
 * Gets the items in the cart.
 *
 * @return array The cart contents as an associative array.
 */
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart doesn't exist
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateCartTotal() {
  $total = 0;
  $cart = getCartContents();
  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return $total;
}


// --- Example Usage ---

// Add some items to the cart
addToCart('product1', 'Awesome T-Shirt', 2, 25.00);
addToCart('product2', 'Cool Mug', 1, 10.00);
addToCart('product3', 'Fancy Hat', 3, 15.00);

// Display the cart contents
$cartContents = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
}

echo "<p><strong>Total Cart Value:</strong> $" . calculateCartTotal() . "</p>";

// Update quantity
updateCartItem('product1', 3);

echo "<p><strong>Updated Cart Value:</strong> $" . calculateCartTotal() . "</p>";


// Remove an item
removeFromCart('product2');

echo "<p><strong>Updated Cart Value:</strong> $" . calculateCartTotal() . "</p>";

// Display the cart contents after removal
$cartContents = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
}
?>
