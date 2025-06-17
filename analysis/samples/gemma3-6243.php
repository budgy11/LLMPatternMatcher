

<?php

// 1. Initialize the session if it doesn't exist
session_start();

// 2. Define a function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart session variable exists. If not, initialize it as an array.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart.
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already in the cart - increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in the cart - add it
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// 3. Example usage:  Let's say we have product IDs 1, 2, and 3

// Add product 1 to the cart - quantity 2
addToCart(1, "Awesome T-Shirt", 20, 2);

// Add product 2 to the cart - quantity 1
addToCart(2, "Cool Hat", 15, 1);

// Add product 1 again - quantity 1 (to demonstrate increasing quantity)
addToCart(1, "Awesome T-Shirt", 20, 1);

// 4. Display the cart contents (for demonstration purposes)
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

echo "<p><strong>Total Cost:</strong> $" .  totalCartCost() . "</p>";
?>
