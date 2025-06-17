

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions for Cart Operations ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.  Defaults to 1.
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
 * @return array  An array representing the cart contents.
 */
function get_cart_contents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int
 */
function cart_total_items() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
      $total = $total + $quantity;
    }
  }
  return $total;
}

/**
 * Calculates the total cost of the cart (assuming products have a price).
 *
 *  This assumes you have a function to get the price of a product based on its ID.
 *
 * @param callable $getPrice A function that takes a product ID and returns the price.
 * @return float The total cost of the cart.
 */
function cart_total_cost($getPrice) {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
      $price = $getPrice($product_id);
      $total_for_item = $price * $quantity;
      $total = $total + $total_for_item;
    }
  }
  return $total;
}



// --- Example Usage (Illustrative) ---

//  Simulated Product Data (Replace with your actual database or data source)
$products = [
  1 => ['name' => 'Shirt', 'price' => 20],
  2 => ['name' => 'Pants', 'price' => 30],
  3 => ['name' => 'Shoes', 'price' => 50],
];


// Add a shirt to the cart
add_to_cart(1);

// Add 2 shirts to the cart
add_to_cart(1, 2);

// Add pants to the cart
add_to_cart(2);

// Remove shoes from the cart
remove_from_cart(3);

// Update the quantity of shirts to 3
update_cart_quantity(1, 3);


// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
if (isset($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_name = $products[$product_id]['name'];
    $product_price = $products[$product_id]['price'];
    $item_total = $product_price * $quantity;

    echo "<li>Product: $product_name (Quantity: $quantity) - Price: $product_price - Total: $item_total</li>";
  }
} else {
  echo "<li>Cart is empty.</li>";
}
echo "</ul>";

echo "<p>Total items in cart: " . cart_total_items() . "</p>";
echo "<p>Total cart cost: " . number_format(cart_total_cost(function($id) {
    return $products[$id]['price'];
})) . "</p>";


?>
