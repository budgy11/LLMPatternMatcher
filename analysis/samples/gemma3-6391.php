

<?php

// Start the session
session_start();

// Define a function to add items to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  // Check if the cart already exists in the session
  if (!isset($_SESSION['cart'])) {
    // If not, initialize the cart as an empty array
    $_SESSION['cart'] = [];
  }

  // Add the item to the cart (associative array for item details)
  $_SESSION['cart'][] = [
    'id' => $item_id,
    'name' => $item_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Example Usage (Simulating adding items to the cart)
addToCart(1, "Shirt", 25.00, 2);
addToCart(2, "Jeans", 50.00, 1);
addToCart(3, "Shoes", 75.00); // Default quantity is 1

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }

  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

?>
