

<?php
session_start();

// Session variables for the cart
$cart = [];

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $item_price, $quantity = 1) {
  global $cart;

  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    $cart[$item_id] = [
      'name' => $item_name,
      'price' => $item_price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
  global $cart;

  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_quantity($item_id, $new_quantity) {
  global $cart;

  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $new_quantity;
  }
}


// Example usage -  Simulating a product listing

$products = [
  1 => ['name' => 'Shirt', 'price' => 25],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 75],
];


// Example Cart Interactions

// 1. Add an item to the cart
add_to_cart(1, 'Shirt', 25, 2);  // Add 2 shirts
add_to_cart(2, 'Jeans', 50, 1); // Add 1 pair of jeans
add_to_cart(3, 'Shoes', 75, 1); // Add 1 pair of shoes

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item_id => $item_details) {
    $name = $item_details['name'];
    $price = $item_details['price'];
    $quantity = $item_details['quantity'];
    $total_item_price = $price * $quantity;

    echo "<li>" . $name . " - $" . $price . " x " . $quantity . " = $" . $total_item_price . "</li>";
  }
  echo "</ul>";

  // Calculate total cart value
  $total_cart_value = 0;
  foreach ($cart as $item_id => $item_details) {
    $total_item_price = $item_details['price'] * $item_details['quantity'];
    $total_cart_value += $total_item_price;
  }
  echo "<p><strong>Total Cart Value: $" . number_format($total_cart_value, 2) . "</strong></p>";
}


// 2. Remove an item from the cart (e.g., remove one pair of jeans)
//remove_from_cart(2);
//echo "<p>Jeans removed from cart.</p>";

// 3. Update the quantity of an item (e.g., increase the quantity of shirts to 3)
//update_quantity(1, 3);
//echo "<p>Shirt quantity updated to 3.</p>";


?>
