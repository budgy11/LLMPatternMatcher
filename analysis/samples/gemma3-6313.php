

<?php

session_start();

// Session variables for the cart
$cart = array();

// Helper functions for cart operations

/**
 * Add an item to the cart.
 *
 * @param string $product_id   The ID of the product being added.
 * @param string $name         The name of the product.
 * @param int    $quantity   The quantity of the product to add.
 * @param float  $price       The price of a single unit of the product.
 */
function addToCart(string $product_id, string $name, int $quantity, float $price) {
  $product = array(
    'id' => $product_id,
    'name' => $name,
    'quantity' => $quantity,
    'price' => $price
  );

  // Check if the product is already in the cart
  foreach ($cart as $key => $item) {
    if ($item['id'] == $product['id']) {
      // Update the quantity if the product already exists
      $cart[$key]['quantity'] += $quantity;
      return;
    }
  }

  // If the product is not in the cart, add it
  $cart[$product['id']] = $product;
}


/**
 * Get the total price of the cart.
 *
 * @return float  The total price.
 */
function getCartTotal() {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['quantity'] * $item['price'];
  }
  return round($total, 2); // Round to 2 decimal places
}

// Example usage:  Simulating a user adding items to the cart
addToCart('product1', 'T-Shirt', 2, 20.00);
addToCart('product2', 'Jeans', 1, 50.00);
addToCart('product3', 'Shoes', 1, 80.00);



// Display the cart contents
echo "<h2>Cart Contents</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
  echo "<p>Total: $" . getCartTotal() . "</p>";
}


// Example:  Clearing the cart (for demonstration purposes)
//session_unset($_SESSION['cart']);
//session_destroy();

?>
