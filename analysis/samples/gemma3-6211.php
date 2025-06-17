

<?php

session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it as an array
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
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

// Example Usage:  (Replace with your actual product IDs, names, and prices)
// This simulates adding a product to the cart
addToCart(1, "T-Shirt", 20, 2); // Product ID 1, T-Shirt, price $20, quantity 2
addToCart(2, "Jeans", 50, 1);   // Product ID 2, Jeans, price $50, quantity 1
addToCart(1, "T-Shirt", 20, 3); // Add another T-Shirt to the cart
// Display Cart Contents (for demonstration - remove in a real application)
echo "<h2>Cart Contents</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}
?>
