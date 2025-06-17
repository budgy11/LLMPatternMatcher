

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes an item from the cart by product ID.
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets the items in the cart.
 *
 * @return array The cart contents.
 */
function getCartContents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total price of items in the cart.
 *
 * @return float The total price.
 */
function calculateTotal() {
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // In a real application, you'd fetch the product price here.
    // This is just a placeholder.
    $product_price = 10;  // Example price
    $total_for_item = $product_price * $quantity;
    $total += $total_for_item;
  }
  return $total;
}



// --- Example Usage (Demonstration) ---

// Add a product to the cart
addToCart(123, 2); // Product ID 123, quantity 2
addToCart(456, 1); // Product ID 456, quantity 1

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = getCartContents();

if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
}
echo "</ul>";

// Calculate and display the total
$total = calculateTotal();
echo "<p>Total Price: $" . $total . "</p>";


// Remove an item from the cart
removeFromCart(456);

// Update the quantity of an item
updateQuantity(123, 3);

// Display the updated cart contents
echo "<p>Updated Cart Contents:</p>";
$cart_items = getCartContents();

if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
}

echo "<p>Total Price: $" . calculateTotal() . "</p>";
?>
