

<?php
session_start();

// Simulate adding items to the cart
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    // Item already exists in the cart, increase quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Add a new item to the cart
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example usage:  Add some items to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 3); // Add more of the same item
addToCart(3, 'Keyboard', 75, 1);


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
    $total_price = $price * $quantity;

    echo "<li>" . $name . " - Price: $" . $price . ", Quantity: " . $quantity . ", Total: $" . $total_price . "</li>";
  }
  echo "</ul>";

  // Calculate total cart value
  $total_value = 0;
  foreach ($cart as $item_id => $item_details) {
    $total_value += $item_details['price'] * $item_details['quantity'];
  }
  echo "<p><strong>Total Cart Value: $" . $total_value . "</strong></p>";
}
?>
