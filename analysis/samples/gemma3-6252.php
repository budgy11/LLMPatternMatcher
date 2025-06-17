

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// ---  Functions to Manage the Cart ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
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
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $new_quantity;
  }
}

/**
 * Gets the cart contents.
 *
 * @return array  An array representing the cart contents (product_id => quantity).
 */
function get_cart_contents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total price of the cart.
 *
 * @param array $cart_contents The cart contents array.
 * @return float  The total price.  Returns 0 if cart is empty.
 */
function calculate_total($cart_contents) {
  $total = 0;
  foreach ($cart_contents as $product_id => $quantity) {
    //  Assume you have a product details database to get the price
    $product_price = get_product_price($product_id);
    if($product_price > 0) {
        $total_for_item = $product_price * $quantity;
        $total += $total_for_item;
    }
  }
  return $total;
}

/**
 * Placeholder function to get product price.  You'll need to implement this
 * based on how your product information is stored.
 *
 * @param int $product_id The ID of the product.
 * @return float  The price of the product.
 */
function get_product_price($product_id) {
  //  Replace this with your actual database query or logic to retrieve the price.
  //  This is just a placeholder.
  switch ($product_id) {
    case 1: return 10.00;
    case 2: return 25.50;
    case 3: return 5.75;
    default: return 0; // Handle unknown product ID
  }
}



// --- Example Usage (Illustrative - for demonstration) ---

// 1. Add a product to the cart
add_to_cart(1); // Add one of product ID 1
add_to_cart(2, 2); // Add two of product ID 2

// 2. Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_contents = get_cart_contents();
if (empty($cart_contents)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_contents as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
}
echo "</ul>";


// 3. Calculate and display the total
$total = calculate_total($cart_contents);
echo "<p>Total: $" . $total . "</p>";


// 4. Remove a product
remove_from_cart(1);

// 5. Update the quantity of a product
update_cart_quantity(2, 3);


// 6. Display the cart contents after updates
echo "<h2>Cart Contents (After Updates):</h2>";
$cart_contents = get_cart_contents();
if (empty($cart_contents)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_contents as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
}
echo "</ul>";

$total = calculate_total($cart_contents);
echo "<p>Total: $" . $total . "</p>";


?>
