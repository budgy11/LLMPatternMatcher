

<?php

// Start a session (if not already started)
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product (for display purposes).
 * @param int $price The price of the product.
 * @param int $quantity The quantity to add (default is 1).
 */
function add_to_cart(string $product_id, string $product_name, float $price, int $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if product exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 */
function update_cart_quantity(string $product_id, int $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function remove_from_cart(string $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculate_cart_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

/**
 * Gets the contents of the cart.
 *
 * @return array The cart contents.
 */
function get_cart_contents() {
  return $_SESSION['cart'];
}


// --- Example Usage (Illustrative - Replace with actual product data) ---

// Add some products to the cart
add_to_cart("product1", "Laptop", 1200.00, 1);
add_to_cart("product2", "Mouse", 25.00, 2);
add_to_cart("product3", "Keyboard", 75.00, 1);


// Example: Update the quantity of product2 to 3
update_cart_quantity("product2", 3);


// Example: Remove product1 from the cart
// remove_from_cart("product1");


// Get and display the cart contents
$cart_contents = get_cart_contents();

echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - Quantity: " . $product_details['quantity'] . " - Price: $" . $product_details['price'] . "</li>";
  }
  echo "</ul>";
}

echo "<p>Total Cart Value: $" . calculate_cart_total() . "</p>";


?>
