

<?php
session_start();

// Simulate adding items to the cart
$cart = []; // Initialize an empty cart (array)

// Function to add an item to the cart
function addToCart($itemId, $itemName, $price, $quantity = 1) {
  global $cart; // Access the global $cart array

  if (isset($cart[$itemId])) {
    // Item already exists in cart, increase quantity
    $cart[$itemId]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add a new entry
    $cart[$itemId] = [
      'name' => $itemName,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Example: Adding some items
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);
addToCart(1, "T-Shirt", 20.00, 3); //Add more of the shirt

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $itemId => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate total cart value
  $total = 0;
  foreach ($cart as $itemId => $item) {
    $total += ($item['price'] * $item['quantity']);
  }
  echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}
?>
