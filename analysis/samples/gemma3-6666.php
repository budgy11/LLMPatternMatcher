

<?php

// Session start (if not already started)
session_start();

// --- Cart Logic ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart(int $product_id, int $quantity = 1) {
  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, update the quantity
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    // If not, add the product to the cart
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart_quantity(int $product_id, int $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    // Handle the case where the product is not in the cart
    // You might want to log an error or prevent the update.
    // For this example, we'll just return without doing anything.
    return;
  }
}


/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array  An array containing the items in the cart.  Returns an empty array if the cart is empty.
 */
function get_cart_items() {
  return $_SESSION['cart'] ?? []; // Use null coalescing operator for safe array retrieval.
}

/**
 * Gets the total number of items in the cart
 *
 * @return int
 */
function get_cart_total() {
  $total = 0;
  foreach(get_cart_items() as $quantity => $product_id) {
    $total = $total + $quantity;
  }
  return $total;
}


// --- Example Usage ---

// Add a product to the cart
add_to_cart(123, 2); // Add 2 of product ID 123

// Update the quantity of a product
update_cart_quantity(123, 5); // Update the quantity of product 123 to 5

// Get the cart items
$cart_items = get_cart_items();
echo "Cart Items: " . print_array($cart_items) . "<br>";


// Remove a product from the cart
remove_from_cart(456);

// Get the cart items again
$cart_items = get_cart_items();
echo "Cart Items: " . print_array($cart_items) . "<br>";

// Get the total
$total = get_cart_total();
echo "Cart Total: " . $total . "<br>";


/**
 * Helper function to print an array nicely.
 *
 * @param array $array The array to print.
 * @return string
 */
function print_array(array $array) {
    $output = "[";
    $count = 0;
    foreach ($array as $key => $value) {
      $output .= $value . ", ";
      $count++;

      if ($count < count($array)) {
          $output .= "  " . $key . ", ";
      } else {
          $output .= "  " . $key;
      }
    }
    $output .= "]";
    return $output;
}

?>
