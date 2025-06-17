

<?php
session_start();

// Cart data (in a real application, this would likely come from a database)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    // Item already in cart, increase quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $cart[$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  global $cart;
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
  global $cart;
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get the total cart value
function get_cart_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}


// Example usage -  Simulating adding items to the cart
add_to_cart(1, "T-Shirt", 20, 2);
add_to_cart(2, "Jeans", 50, 1);
add_to_cart(1, "T-Shirt", 20, 3); // Adding more of the same item

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . get_cart_total() . "</strong></p>";
}

// Example of removing an item
//remove_from_cart(2);

// Example of updating the quantity
//update_quantity(1, 5);


?>
