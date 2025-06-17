

<?php

// Start a session (if not already started)
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart(int $product_id, int $quantity = 1) {
  // Check if the cart already exists in the session.
  if (!isset($_SESSION['cart'])) {
    // If not, initialize the cart as an empty array.
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If the product exists, increment the quantity.
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    // If the product doesn't exist, add it to the cart with the specified quantity.
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
    if (!isset($_SESSION['cart'][$product_id])) {
        // Product not in cart, add it
        addToCart($product_id, $quantity);
        return;
    }
    $_SESSION['cart'][$product_id] = $quantity;
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeCartItem(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets the contents of the cart.
 *
 * @return array The cart contents (an array of items with their quantities).
 */
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart doesn't exist
}


/**
 * Gets the total number of items in the cart.
 *
 * @return int
 */
function getCartItemCount() {
    return count(getCartContents());
}


/**
 * Gets the total value of the cart.  This assumes a fixed price per item.
 *
 * @param array $cartContents  The cart contents array.
 * @param int $productPrice The price of a single product.
 * @return float
 */
function getCartTotal(array $cartContents, int $productPrice) {
    $total = 0;
    foreach ($cartContents as $product_id => $quantity) {
        $totalForProduct = $productPrice * $quantity;
        $totalForProduct = $productPrice * $quantity;
        $total += $totalForProduct;
    }
    return $total;
}


// --- Example Usage (for demonstration) ---

// Add some items to the cart:
addToCart(101, 2); // Product ID 101, quantity 2
addToCart(102, 1); // Product ID 102, quantity 1
updateCartQuantity(101, 3); // Update quantity of product 101 to 3

// Display the cart contents:
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
}

// Get the item count:
$itemCount = getCartItemCount();
echo "<br>Total Items in Cart: " . $itemCount . "<br>";

// Get the cart total (assuming a product price of $20)
$productPrice = 20;
$cartTotal = getCartTotal($cart, $productPrice);
echo "Cart Total: $" . $cartTotal . "<br>";

// Remove an item:
removeCartItem(102);

// Display cart contents after removal
$cart = getCartContents();
echo "<h2>Cart Contents After Removal:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
}

?>
