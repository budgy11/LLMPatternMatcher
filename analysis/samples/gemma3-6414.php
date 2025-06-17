

<?php
session_start();

// Assuming you have a function or mechanism to handle adding items to the cart
function addItemToCart($itemId, $itemName, $price, $quantity) {
  // In a real application, you'd likely interact with a database here.
  // This is just a simplified example.

  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][] = array(
    'id' => $itemId,
    'name' => $itemName,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Example:  Simulating a user adding an item to the cart.
// Replace these with your actual item and quantity information.
addItemToCart(1, 'Awesome T-Shirt', 25.00, 1);
addItemToCart(2, 'Cool Hat', 15.00, 2);


// Display the cart contents
echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate the total cart value.
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}
?>
