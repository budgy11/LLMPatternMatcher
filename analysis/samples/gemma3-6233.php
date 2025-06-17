

<?php
session_start();

// Array to hold cart items (key is product ID, value is quantity)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1)
{
  global $cart;

  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = ['quantity' => $quantity];
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id)
{
  global $cart;

  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity)
{
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $quantity;
  }
}


// Example Usage -  Simulating some actions to populate the cart

// Add some items to the cart
add_to_cart(1, 2); // Product ID 1, Quantity 2
add_to_cart(2, 1); // Product ID 2, Quantity 1
add_to_cart(1, 3); // Product ID 1, Quantity 3  (update existing item)

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item_details) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item_details['quantity'] . "</li>";
  }
  echo "</ul>";
}

//  Simulating a remove action
// remove_from_cart(2);

//  Simulating an update action
// update_cart_quantity(1, 5);

// Display updated cart
echo "<h2>Updated Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item_details) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item_details['quantity'] . "</li>";
  }
  echo "</ul>";
}
?>
