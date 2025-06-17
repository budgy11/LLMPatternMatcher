

<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions to Manage the Cart ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add. Defaults to 1.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


/**
 * Gets all items in the cart.
 *
 * @return array An array containing the products in the cart.
 */
function get_cart_items() {
  return $_SESSION['cart'];
}


/**
 * Calculates the total price of the cart.
 * 
 * @param array $cart_items The cart items to use for the calculation.
 * @return float The total price.
 */
function calculate_total(array $cart_items) {
    $total = 0;
    foreach ($cart_items as $product_id => $quantity) {
        // Assuming you have a product database/data to get the price
        // Replace this with your actual price retrieval logic
        $product_price = get_product_price($product_id);  
        $total_price_for_item = $product_price * $quantity;
        $total += $total_price_for_item;
    }
    return $total;
}


// --- Example Usage (Simulated) ---

// Add some products to the cart
add_to_cart(1, 2); // Product ID 1, quantity 2
add_to_cart(2, 1); // Product ID 2, quantity 1
add_to_cart(1, 3); // Product ID 1, quantity 3

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_items = get_cart_items();

foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";


// Calculate and display the total
$total = calculate_total($cart_items);
echo "<p><strong>Total Price: $" . $total . "</strong></p>";


// Remove a product from the cart
remove_from_cart(2);

// Display the cart contents after removal
echo "<p><strong>Cart Contents after removing product 2:</strong></p>";
$cart_items = get_cart_items();

foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}

// Update quantity of product 1
update_cart_quantity(1, 5);
echo "<p><strong>Cart Contents after updating quantity of product 1 to 5:</strong></p>";

$cart_items = get_cart_items();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}

?>
