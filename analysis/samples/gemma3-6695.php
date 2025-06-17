

<?php
session_start();

// Session variables for the cart
$_SESSION['cart'] = array(); // Initialize the cart as an empty array

// Helper functions for cart operations (can be moved to a separate file)
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  $_SESSION['cart'][] = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

function get_cart_items() {
  return $_SESSION['cart'];
}

function update_quantity($product_id, $quantity) {
  // Iterate through the cart items
  foreach ($_SESSION['cart'] as &$item) { // Use &$item for reference to modify the original array
    if ($item['id'] == $product_id) {
      $item['quantity'] = $quantity;
      return;
    }
  }
}

function remove_from_cart($product_id) {
  // Iterate through the cart and remove the item
  $keys = array_keys($_SESSION['cart']);
  foreach ($keys as $key) {
    if ($_SESSION['cart'][$key]['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      break;
    }
  }
}

function calculate_total() {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Example Usage (demonstration - this would be part of a web form)

// Add a product to the cart
add_to_cart(1, 'T-Shirt', 20, 2); // Product ID 1, T-Shirt, price $20, quantity 2
add_to_cart(2, 'Jeans', 50, 1); // Product ID 2, Jeans, price $50, quantity 1

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculate_total() . "</strong></p>";
}


// Example of updating the quantity
update_quantity(1, 3); // Increase the quantity of T-Shirt to 3

// Display the updated cart
echo "<h2>Your Updated Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculate_total() . "</strong></p>";
}

// Example of removing an item
remove_from_cart(2);

//Display the cart after removing an item
echo "<h2>Your Updated Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculate_total() . "</strong></p>";
}
?>
