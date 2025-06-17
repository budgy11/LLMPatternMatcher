

<?php

// Start a session if it doesn't exist
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Function to Add to Cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already exists - increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart - add it
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}


// --- Example Usage (Simulated Cart Manipulation) ---

// Add some products to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);
addToCart(1, 'T-Shirt', 20, 3); // Add more of the T-Shirt
addToCart(3, 'Hat', 15, 1);


// --- Display the Cart Contents ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $product_name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];

    echo "<li>" . $product_name . " - $" . $price . " x " . $quantity . " = $" . ($price * $quantity) . "</li>";
  }
  echo "</ul>";

  // Calculate total cost
  $total_cost = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $total_cost += $product_details['price'] * $product_details['quantity'];
  }
  echo "<p><strong>Total Cost: $" . number_format($total_cost, 2) . "</strong></p>";
}


// ---  Example:  Remove a Product from Cart (Optional) ---
// This is just an example, you'd likely implement this with a form.
/*
if (isset($_GET['remove'])) {
  $product_id = $_GET['remove'];

  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}
*/

?>
