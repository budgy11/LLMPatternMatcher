

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions for Cart Management ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param string $name The name of the product.
 * @param float $price The price of the product.
 * @param int $quantity The quantity of the product to add (default: 1).
 */
function add_to_cart($product_id, $name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 */
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets the cart contents.
 *
 * @return array The contents of the cart.
 */
function get_cart() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total cost of the cart.
 *
 * @return float The total cost.
 */
function calculate_total() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}


// --- Example Usage ---

// 1. Add an item to the cart
add_to_cart(1, "Awesome T-Shirt", 20.00, 2);
add_to_cart(2, "Cool Mug", 10.00);


// 2. Update the quantity of an item
update_cart_quantity(1, 3); // Increase quantity of Awesome T-Shirt to 3

// 3. Get the cart contents
$cart_items = get_cart();
print_r($cart_items);

// 4. Calculate the total cost
$total = calculate_total();
echo "Total cost: $" . number_format($total, 2) . "<br>";

// 5. Remove an item from the cart
remove_from_cart(2);

// 6. Get the cart contents after removal
$cart_items = get_cart();
print_r($cart_items);
$total = calculate_total();
echo "Total cost: $" . number_format($total, 2) . "<br>";


?>
