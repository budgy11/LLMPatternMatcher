

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Example Functions to Add Items to the Cart ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product being added.
 * @param string $product_name The name of the product.
 * @param int    $quantity   The quantity of the product to add (default: 1).
 * @param float  $price      The price per unit of the product.
 *
 * @return void
 */
function add_to_cart(string $product_id, string $product_name, int $quantity = 1, float $price = 0.0) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'quantity' => $quantity,
      'price' => $price
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $quantity   The new quantity.
 * @return void
 */
function update_cart_quantity(string $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// --- Example Usage (Demonstration) ---

// Add a few items to the cart
add_to_cart('product1', 'Laptop', 1, 1200.00);
add_to_cart('product2', 'Mouse', 2, 25.00);
add_to_cart('product3', 'Keyboard', 1, 75.00);

// Display the contents of the cart
echo "<h2>Your Cart:</h2>";
echo "<ul>";
if (empty($_SESSION['cart'])) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
}
echo "</ul>";

// Example: Update the quantity of a product
update_cart_quantity('product2', 3);

// Display the updated cart
echo "<h2>Your Cart (Updated):</h2>";
echo "<ul>";
if (empty($_SESSION['cart'])) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
}
echo "</ul>";

// Example: Remove an item from the cart
remove_from_cart('product1');

// Display the updated cart
echo "<h2>Your Cart (After Removal):</h2>";
echo "<ul>";
if (empty($_SESSION['cart'])) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
}
echo "</ul>";
?>
