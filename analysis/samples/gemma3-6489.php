

<?php

// Start a session to store the user's cart
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, update the quantity
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


// Example Usage:  Let's say we have product IDs 1, 2, and 3
// Add product 1 to the cart (quantity 2)
addToCart(1, "T-Shirt", 20.00, 2);

// Add product 2 to the cart (quantity 1)
addToCart(2, "Jeans", 50.00, 1);

// Add product 3 to the cart (quantity 3)
addToCart(3, "Hat", 15.00, 3);


// Display the contents of the cart
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
