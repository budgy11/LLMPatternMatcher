

<?php

// Start a session
session_start();

// Array to store cart items (name, quantity, price)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
  $item = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // Check if the item is already in the cart
  foreach ($cart as &$item_in_cart) {
    if ($item_in_cart['id'] == $item['id']) {
      $item_in_cart['quantity'] += $item['quantity'];
      break;
    }
  }

  // If the item is not in the cart, add it
  else {
    $cart[] = $item;
  }
}

// Function to get the cart total
function get_cart_total() {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Function to display the cart contents
function display_cart() {
  echo "<h2>Your Shopping Cart</h2>";
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart as $index => $item) {
      echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . number_format(get_cart_total(), 2) . "</strong></p>";
  }
}

// Example Usage:

// Add some items to the cart
add_to_cart(1, "T-Shirt", 20.00, 2);
add_to_cart(2, "Jeans", 50.00, 1);
add_to_cart(1, "T-Shirt", 20.00, 1); // Add another T-Shirt

// Display the cart contents
display_cart();

//  Simulate a user removing an item (Example) -  You would likely have a form for this
// $remove_item_id = 1;
// remove_from_cart($remove_item_id); //  Would need a remove_from_cart function (implementation not provided)

?>
