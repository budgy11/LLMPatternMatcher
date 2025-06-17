

<?php
session_start();

// --- Shopping Cart Functionality ---

/**
 * Adds an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart(int $product_id, int $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If the product exists, increment the quantity
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    // If the product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateCartQuantity(int $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Removes an item from the shopping cart.
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
 * Gets the contents of the shopping cart.
 *
 * @return array The shopping cart contents.
 */
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Use null coalesce operator for empty array safety
}

// --- Example Usage (for demonstration) ---

// Add some items to the cart
addToCart(1, 2); // Add 2 of product ID 1
addToCart(2, 1); // Add 1 of product ID 2
addToCart(1, 3); // Add 3 of product ID 1

// Display the cart contents
$cart = getCartContents();
echo "<h2>Shopping Cart:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $quantity) {
    // Get product details (replace with your actual database or product data)
    $product = getProductDetails($product_id); // Placeholder function

    if ($product) {
      echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    } else {
      echo "<li>Product ID: $product_id (Product details not found)</li>";
    }
  }
  echo "</ul>";
}

// Example: Update quantity
updateCartQuantity(1, 5); // Change quantity of product 1 to 5

//Example: Remove an item
//removeFromCart(2);

// Display the updated cart contents
$cart = getCartContents();
echo "<h2>Shopping Cart (Updated):</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $quantity) {
    // Get product details (replace with your actual database or product data)
    $product = getProductDetails($product_id); // Placeholder function

    if ($product) {
      echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    } else {
      echo "<li>Product ID: $product_id (Product details not found)</li>";
    }
  }
  echo "</ul>";
}
?>
