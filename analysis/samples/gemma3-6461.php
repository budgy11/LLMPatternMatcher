

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// ------------------- Cart Functions -------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 0; // Initialize count if not exists
  }
  $_SESSION['cart'][$product_id] += $quantity;
}

/**
 * Removes an item from the cart.
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity for the product.
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
 * @return array An array containing the items in the cart.
 */
function get_cart() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total cart value.
 *
 * @return float The total value of the cart.
 */
function calculate_cart_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    //  Replace this with your actual product price retrieval.
    //  For example:
    $product_price = get_product_price($product_id); // Function to get price
    $total_item_price = $product_price * $quantity;
    $total += $total_item_price;
  }
  return $total;
}

// ------------------- Example Usage (For Demonstration) -------------------

// Add a product to the cart
add_to_cart(123, 2);  // Add 2 units of product ID 123
add_to_cart(456, 1);  // Add 1 unit of product ID 456

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
foreach (get_cart() as $product_id => $quantity) {
  echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";

// Calculate and display the total
$total = calculate_cart_total();
echo "<br><h2>Total Cart Value: $" . $total . "</h2>";


// Example of removing an item
//remove_from_cart(123);
//echo "<br><h2>Cart Contents After Removal:</h2>";
//foreach (get_cart() as $product_id => $quantity) {
//    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
//}

//Example of updating a quantity
//update_cart_quantity(123, 3);
//echo "<br><h2>Cart Contents After Quantity Update:</h2>";
//foreach (get_cart() as $product_id => $quantity) {
//    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
//}

?>
