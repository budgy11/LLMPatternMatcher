

<?php

session_start();

// --------------------- Cart Functions ---------------------

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add. Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
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
  return $_SESSION['cart'] ?? []; // Use null coalesce operator for empty array
}


/**
 * Calculates the total price of all items in the cart.
 *
 * @return float The total price.
 */
function calculateTotal() {
  $total = 0;
  $cart = getCart();

  foreach ($cart as $product_id => $item) {
    // Assuming you have a way to get the price of a product based on its ID.
    // This is just an example - you'll need to adapt this to your product data.
    $price = getProductPrice($product_id); // Implement this function
    $total_for_item = $price * $item['quantity'];
    $total = $total + $total_for_item;
  }

  return $total;
}



// --------------------- Helper Functions (Implement these) ---------------------

/**
 * Placeholder function to get the price of a product.  You need to replace this.
 * This is just an example.  You'll need to retrieve product prices from your database or other source.
 *
 * @param string $product_id The ID of the product.
 * @return float The price of the product.
 */
function getProductPrice($product_id) {
    // *** REPLACE THIS WITH YOUR ACTUAL LOGIC TO RETRIEVE THE PRODUCT PRICE ***
    // Example:
    if ($product_id == 'product1') {
        return 10.00;
    } elseif ($product_id == 'product2') {
        return 20.00;
    } else {
        return 0; // Or handle the error as appropriate.
    }
}

// --------------------- Example Usage ---------------------

// Add some products to the cart
addToCart('product1', 2);
addToCart('product2', 1);
addToCart('product1', 1); // Add more of product1

// Display the contents of the cart
echo "<h2>Cart Items</h2>";
$cart = getCart();
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Price: $" . number_format(calculateTotal(), 2) . "</p>";

// Update the quantity of a product
updateCartQuantity('product1', 3);

// Remove an item from the cart
removeFromCart('product2');

// Display the updated cart
echo "<h2>Updated Cart Items</h2>";
$cart = getCart();

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Price: $" . number_format(calculateTotal(), 2) . "</p>";

?>
