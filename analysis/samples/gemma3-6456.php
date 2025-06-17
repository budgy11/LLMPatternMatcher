

<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int    $price  The price of the product.
 * @param int    $quantity The quantity to add (default: 1).
 */
function add_to_cart(string $product_id, string $name, float $price, int $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $name,
      'price' => $price,
      'quantity' => $quantity
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $quantity The new quantity.
 */
function update_cart_quantity(string $product_id, int $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


/**
 * Removes a product from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function remove_from_cart(string $product_id) {
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
  return $_SESSION['cart'] ?? []; // Use null coalesce operator for default empty array
}


/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculate_total() {
    $total = 0.0;
    $cart = get_cart_contents();
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// --- Example Usage (Simulating a shopping cart) ---

// 1. Add some items to the cart
add_to_cart('product1', 'Awesome T-Shirt', 25.00, 2);
add_to_cart('product2', 'Cool Mug', 10.00, 1);
add_to_cart('product3', 'Fancy Hat', 30.00); // Add one of this item


// 2. Update the quantity of a product
update_cart_quantity('product1', 5); // Increase the quantity of Awesome T-Shirt to 5


// 3. View the cart contents
$cart_items = get_cart_contents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

//4. Calculate the total
$total = calculate_total();
echo "<h2>Total Cart Value: $" . $total . "</h2>";

// 5. Remove an item from the cart
remove_from_cart('product2');

//6. View the updated cart
$cart_items = get_cart_contents();
echo "<h2>Cart Contents after removal:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}
?>
