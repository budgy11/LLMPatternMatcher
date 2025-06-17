

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions to interact with the cart ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add (default: 1).
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart with a quantity of 1
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity, 'price' => $product_price); // Assuming you have a $product_price variable
  }
}

/**
 * Removes an item from the cart.
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
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity  The new quantity.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


// --- Example Usage (Illustrative - Replace with your actual logic) ---

//  Simulate retrieving product data (replace with your database query)
$product1_id = 1;
$product1_price = 20;

$product2_id = 2;
$product2_price = 35;

// Add product 1 to the cart with a quantity of 2
addToCart($product1_id, 2);

// Add product 2 to the cart with a quantity of 1
addToCart($product2_id);

// Update the quantity of product 1 to 5
updateQuantity($product1_id, 5);


// Display the cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: " . $item['price'] . "</li>";
  }
  echo "</ul>";
}

// Example of removing an item from the cart
// removeFromCart($product2_id);

?>
