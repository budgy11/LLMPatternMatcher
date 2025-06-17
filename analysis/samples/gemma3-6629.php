

<?php

// Start a session (if it doesn't exist)
session_start();

// --- Cart Management Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Removes a product from the cart.
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
 * Gets the cart contents.
 *
 * @return array The cart contents as an array.
 */
function getCartContents() {
  return $_SESSION['cart'] ?? [];  // Use null coalescing operator for safety
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
  $total = 0;
  $cart = getCartContents();
  foreach ($cart as $product_id => $item) {
    // Assuming you have a database or other source to get the product price
    $productPrice = getProductPrice($product_id); // Replace with your actual function
    $totalForProduct = $productPrice * $item['quantity'];
    $totalForProduct = $totalForProduct; //No need to recalculate.
    $total += $totalForProduct;
  }
  return $total;
}

/**
 * Placeholder function to retrieve product price.  Replace with your actual logic.
 *
 * @param int $product_id The ID of the product.
 * @return float The product price.
 */
function getProductPrice($product_id) {
  // Replace this with your logic to fetch product prices from a database, etc.
  // This is just a placeholder.
  switch ($product_id) {
    case 1: return 10.00;
    case 2: return 25.50;
    case 3: return 5.00;
    default: return 0.00;
  }
}

// --- Example Usage (Demonstration) ---

// 1. Add some items to the cart
addToCart(1); // Add one of product 1
addToCart(2, 2); // Add two of product 2
addToCart(1, 3); // Add three of product 1


// 2. Display the cart contents
$cartContents = getCartContents();
echo "<h2>Your Cart</h2>";
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// 3. Update quantity of product 1 to 5
updateCartQuantity(1, 5);

// 4. Display the cart contents again after update
echo "<h2>Your Cart (Updated)</h2>";
$cartContents = getCartContents();
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

// 5. Calculate and display the total
$total = calculateTotal();
echo "<p>Total Cart Value: $" . number_format($total, 2) . "</p>";


// 6. Remove a product
removeFromCart(2);
echo "<p>Product 2 has been removed from your cart.</p>";

// 7. Display the cart contents after removal.
echo "<p>Final Cart Contents</p>";
$cartContents = getCartContents();
if (empty($cartContents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}
?>
