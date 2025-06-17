

<?php

// Start the session
session_start();

// --- Cart Data ---

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param string $product_name The name of the product.
 * @param int $price The price of the product.
 * @param int $quantity The quantity of the product being added.
 */
function addToCart(int $product_id, string $product_name, float $price, int $quantity = 1) {
  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'id' => $product_id,
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}


/**
 * Removes a product from the cart by ID.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart(int $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $newQuantity The new quantity of the product.
 */
function updateQuantity(int $product_id, int $newQuantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
    }
}

/**
 * Gets the contents of the cart.
 *
 * @return array The cart contents.
 */
function getCartContents() {
    return $_SESSION['cart'];
}

// --- Example Usage (Illustrative -  This will be handled in your application logic) ---

// Add a product to the cart
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);

// Display the cart contents
$cart_contents = getCartContents();
echo "<h2>Your Cart:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

//Remove a product
removeFromCart(1);

//Update quantity of a product
updateQuantity(2, 3);

//Display the updated cart
echo "<h2>Your Cart (Updated):</h2>";
$cart_contents = getCartContents();
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $product_data) {
        echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
    }
    echo "</ul>";
}


?>
