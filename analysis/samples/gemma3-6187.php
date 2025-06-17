  <?php endif; ?>

  <a href="index.php">Continue Shopping</a> <!-- Link to your product listing page -->

</body>
</html>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --------------------  Functions for Cart Operations --------------------

/**
 * Adds an item to the cart.
 *
 * @param string $product_id  The ID of the product being added.
 * @param string $product_name  The name of the product (for display purposes).
 * @param int    $quantity    The quantity of the product to add.
 * @param float  $price       The price of the product.
 *
 * @return void
 */
function add_to_cart(string $product_id, string $product_name, int $quantity, float $price): void {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'quantity' => $quantity,
      'price' => $price
    );
  } else {
    // Item already in cart, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id  The ID of the product to update.
 * @param int    $quantity  The new quantity of the product.
 *
 * @return void
 */
function update_cart_quantity(string $product_id, int $quantity): void {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id  The ID of the product to remove.
 *
 * @return void
 */
function remove_from_cart(string $product_id): void {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets the contents of the cart.
 *
 * @return array  An array representing the cart contents.
 */
function get_cart_contents(): array {
  return $_SESSION['cart'];
}

// --------------------  Example Usage (for demonstration) --------------------

// Add a product to the cart
add_to_cart('product1', 'Awesome T-Shirt', 2, 25.00);

// Add another product
add_to_cart('product2', 'Cool Mug', 1, 10.00);

// Update the quantity of product1 to 3
update_cart_quantity('product1', 3);

// Get the cart contents
$cart_contents = get_cart_contents();

// Display the cart contents (for demonstration)
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . " per item<br>";
    echo "<strong>Total:</strong> $" . ($product_details['quantity'] * $product_details['price']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Remove an item
remove_from_cart('product2');

// Display cart contents after removal
echo "<hr>";
echo "<h2>Cart Contents After Removal:</h2>";
$cart_contents = get_cart_contents();
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . " per item<br>";
    echo "<strong>Total:</strong> $" . ($product_details['quantity'] * $product_details['price']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
