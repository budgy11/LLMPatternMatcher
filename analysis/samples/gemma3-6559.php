

<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions to manage the cart ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    // Item not in cart, initialize its entry
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'name'     => 'Product Name (Placeholder - Replace with actual product name)', // Replace with actual product name
      'price'    => 0  // Replace with actual product price
    );
  } else {
    // Item already in cart, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  } else {
    // Handle the case where the item doesn't exist in the cart.
    // You might want to add it with the default quantity, or skip it.
    // For this example, we'll skip it.
    // echo "Product ID " . $product_id . " not found in cart.  Skipping update.
";
  }
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
 * Gets the contents of the cart.
 *
 * @return array The cart contents.
 */
function get_cart_contents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total price of the cart.
 *
 * @return float The total price.
 */
function calculate_total() {
  $total = 0.0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}


// --- Example Usage (Demonstration) ---

// 1. Add an item to the cart
add_to_cart(123); // Add 1 of product ID 123
add_to_cart(456, 2); // Add 2 of product ID 456

// 2. View the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_contents = get_cart_contents();
if (empty($cart_contents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_contents as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . "<br>";
    echo "  Quantity: " . $item['quantity'] . "<br>";
    echo "  Price: $" . $item['price'] . "<br>";
    echo "  Total for this item: $" . $item['price'] * $item['quantity'] . "</li>";
  }
}
echo "</ul>";

// 3. Update the quantity of an item
update_cart_quantity(123, 5);

echo "<br>";
echo "<h2>Updated Cart Contents:</h2>";
$cart_contents = get_cart_contents();
if (empty($cart_contents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_contents as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . "<br>";
    echo "  Quantity: " . $item['quantity'] . "<br>";
    echo "  Price: $" . $item['price'] . "<br>";
    echo "  Total for this item: $" . $item['price'] * $item['quantity'] . "</li>";
  }
}

// 4. Remove an item from the cart
remove_from_cart(456);

// 5. Calculate and display the total
$total = calculate_total();
echo "<br><h2>Total Cart Value:</h2>";
echo "<p>Total: $" . $total . "</p>";


?>
