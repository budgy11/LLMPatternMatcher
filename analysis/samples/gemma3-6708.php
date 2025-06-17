

<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Example usage:
// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);  // Product ID 1, Laptop, price 1200, quantity 1
addToCart(2, "Mouse", 25, 2);    // Product ID 2, Mouse, price 25, quantity 2

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    echo "<li>";
    echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Function to remove an item from the cart (optional)
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example of removing an item (optional)
// removeFromCart(2);

?>
