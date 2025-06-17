

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    // If the cart is empty, initialize it as an array
    $_SESSION['cart'] = [];
  }

  // Create an associative array for the item
  $item = [
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // Add the item to the cart array
  $_SESSION['cart'][] = $item;

  // Optionally, update the quantity if the item already exists
  // in the cart.  This is more complex and adds more overhead, so
  //  the simple approach of adding the item is generally preferred
  //  unless you need to precisely track quantities for complex scenarios.

  //  See the comments below for a more advanced version if needed.
}


// Example Usage:
// Assuming you have a product ID, name, and price from a form submission,
// or database retrieval, etc.

// Add one item to the cart
addToCart(1, "T-Shirt", 20.00);

// Add two items to the cart
addToCart(2, "Jeans", 50.00, 2);


// Display the cart contents (for demonstration purposes)
echo "<h2>Your Shopping Cart</h2>";

if (!empty($_SESSION['cart'])) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

// For debugging/development, you can print the session to see its contents.
// This should *not* be in production code.
// echo "<pre>";
// print_r($_SESSION['cart']);
// echo "</pre>";
?>
