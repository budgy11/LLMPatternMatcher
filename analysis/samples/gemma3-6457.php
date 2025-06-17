

<?php
session_start();

// ------------------- Cart Management Functions -------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return bool True if added successfully, false otherwise.
 */
function add_to_cart(int $product_id, int $quantity = 1) {
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'price' => get_product_price($product_id) // Retrieve the price (implementation needed)
    );
  }
  return true;
}

/**
 * Retrieves the price of a product based on its ID.
 * (Placeholder - you'll need to replace this with your actual price retrieval logic)
 *
 * @param int $product_id The ID of the product.
 * @return float The product price.
 */
function get_product_price(int $product_id) {
  // Example:  Replace this with your database query or data source.
  switch ($product_id) {
    case 1:
      return 10.99;
    case 2:
      return 5.50;
    default:
      return 0; // Handle unknown product IDs
  }
}


/**
 * Calculates the subtotal for a given product ID.
 *
 * @param int $product_id The ID of the product.
 * @return float The subtotal for the product.
 */
function get_product_subtotal(int $product_id) {
    return get_product_price($product_id) * $_SESSION['cart'][$product_id]['quantity'];
}

/**
 * Removes an item from the cart by its product ID.
 *
 * @param int $product_id The ID of the product to remove.
 * @return bool True if removed successfully, false otherwise.
 */
function remove_from_cart(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    return true;
  }
  return false;
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clear_cart() {
    unset($_SESSION['cart']);
}

// ------------------- Cart Management Functions End -------------------


// ------------------- Example Usage (Demonstration) -------------------

// 1. Add an item to the cart
add_to_cart(1); // Add one of product ID 1
add_to_cart(2, 2); // Add two of product ID 2

// 2. Display the cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $subtotal = get_product_subtotal($product_id);
    echo "<li>Product ID: " . $product_id . "<br>";
    echo "  Quantity: " . $item['quantity'] . "<br>";
    echo "  Price: $" . number_format(get_product_price($product_id), 2) . "<br>"; // Format price
    echo "  Subtotal: $" . number_format($subtotal, 2) . "</li>";
  }
  echo "</ul>";
}

// 3. Remove an item
remove_from_cart(1);

// 4. Display the updated cart
echo "<hr>";
echo "<h2>Cart Contents After Removal:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $subtotal = get_product_subtotal($product_id);
    echo "<li>Product ID: " . $product_id . "<br>";
    echo "  Quantity: " . $item['quantity'] . "<br>";
    echo "  Price: $" . number_format(get_product_price($product_id), 2) . "<br>"; // Format price
    echo "  Subtotal: $" . number_format($subtotal, 2) . "</li>";
  }
  echo "</ul>";
}

// 5. Clear the cart
//clear_cart();
//echo "<hr>";
//echo "<h2>Cart Contents After Clearing:</h2>";
//if (empty($_SESSION['cart'])) {
//  echo "<p>Your cart is empty.</p>";
//} else {
//  echo "<ul>";
//  foreach ($_SESSION['cart'] as $product_id => $item) {
//    $subtotal = get_product_subtotal($product_id);
//    echo "<li>Product ID: " . $product_id . "<br>";
//    echo "  Quantity: " . $item['quantity'] . "<br>";
//    echo "  Price: $" . number_format(get_product_price($product_id), 2) . "<br>";
//    echo "  Subtotal: $" . number_format($subtotal, 2) . "</li>";
//  }
//  echo "</ul>";
//}

?>
