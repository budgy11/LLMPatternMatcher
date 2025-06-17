

<?php
session_start();

// This is the shopping cart array.  You can change the keys to be more descriptive.
$cart = array();

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $price, $quantity = 1) {
  global $cart;

  if (empty($cart)) {
    $cart[$item_id] = array(
      'name' => $item_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    // Check if the item is already in the cart
    if (isset($cart[$item_id])) {
      $cart[$item_id]['quantity'] += $quantity;
    } else {
      $cart[$item_id] = array(
        'name' => $item_name,
        'price' => $price,
        'quantity' => $quantity
      );
    }
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
function update_cart_quantity($item_id, $quantity) {
  global $cart;
  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $quantity;
  }
}


// Example Usage:
// Add some items to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
add_to_cart(2, 'Jeans', 50, 1);
add_to_cart(1, 'T-Shirt', 20, 3); // Add more of the same item

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($cart as $id => $item) {
    $total += $item['price'] * $item['quantity'];
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// Example of Removing an item
// remove_from_cart(2);

// Example of Updating the quantity
// update_cart_quantity(1, 5);

?>
