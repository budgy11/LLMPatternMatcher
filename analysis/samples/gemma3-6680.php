

<?php

// Start a session (if not already started)
session_start();

// --------------------------------------------------------------------
//  Function to add an item to the cart
// --------------------------------------------------------------------
function addToCart($product_id, $quantity = 1, $product_name = null) {
  // Check if the cart already exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Initialize an empty cart array
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already exists in the cart, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist in the cart, add it with the given quantity
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name ?? $product_id, // Use product name if provided, otherwise use ID
    ];
  }
}

// --------------------------------------------------------------------
//  Example Usage (for demonstration purposes)
// --------------------------------------------------------------------

// Add a product to the cart
addToCart(123, 2);  // Add 2 units of product with ID 123

// Add another product to the cart
addToCart(456, 1, "Awesome T-Shirt"); // Add 1 unit of product with ID 456 and name "Awesome T-Shirt"


// --------------------------------------------------------------------
//  Displaying the Cart Contents (for demonstration)
// --------------------------------------------------------------------

echo "<h2>Your Shopping Cart</h2>";

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "Product ID: " . $product_id . "<br>";
    echo "Product Name: " . $product_details['name'] . "<br>";
    echo "Quantity: " . $product_details['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


?>
