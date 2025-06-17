

<?php

// 1. Session Start
session_start();

// 2.  Define Cart Data (This will be stored in the session)
$cart = [];

// 3.  Functions for Cart Operations

// Add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    // Increment quantity if product exists
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $cart[$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Update the quantity of an item in the cart
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Get the cart contents
function get_cart_contents() {
  return $cart;
}

// Clear the cart
function clear_cart() {
    $cart = [];
}



// 4. Example Usage (Simulating User Interaction)

// Initial Cart State
// You could load this from a database or a file in a real application.
// For this example, we'll just start with an empty cart.

// Add some products to the cart
add_to_cart(1, 'Laptop', 1200, 1);
add_to_cart(2, 'Mouse', 25, 2);
add_to_cart(1, 'Laptop', 1200, 1); // Add another laptop

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";
$cart_items = get_cart_contents();
foreach ($cart_items as $product_id => $item) {
  echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
}
echo "</ul>";

echo "<p><a href='cart.php?action=clear'>Clear Cart</a></p>"; // Example of calling a different page



// Example of updating quantity
// update_cart_quantity(2, 3); // Change the quantity of the mouse to 3
// echo "<h2>Updated Cart</h2>";
// $cart_items = get_cart_contents();
// foreach ($cart_items as $product_id => $item) {
//   echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
// }



// Example of clearing the cart
// clear_cart();
// echo "<h2>Empty Cart</h2>";
// echo "<ul>";
// $cart_items = get_cart_contents();
// foreach ($cart_items as $product_id => $item) {
//   echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
// }
// echo "</ul>";


?>
