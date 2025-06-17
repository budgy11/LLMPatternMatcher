

<?php

// Initialize an empty session if it doesn't exist
session_start();

// Check if the 'cart' session variable exists
if (!isset($_SESSION['cart'])) {
  // If not, initialize it as an empty array
  $_SESSION['cart'] = array();
}

// ---  Example Function to Add Items to Cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the 'cart' session variable exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If not, add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}


// --- Example Usage (Simulated) ---

// Add a product to the cart
addToCart(1, 'T-Shirt', 20.00, 2);

// Add another product
addToCart(2, 'Jeans', 50.00, 1);

// Display the contents of the cart
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

echo "<p><strong>Total Cart Value:</strong> $" . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])) . "</p>";


// ---  Example Function to Remove Items from Cart ---
function removeItemFromCart($product_id) {
  // Check if the 'cart' session variable exists
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example: Remove product with ID 1
// removeItemFromCart(1);

// Display the cart contents after removing an item (for demonstration)
// echo "<h2>Cart Contents After Removal:</h2>";
// if (empty($_SESSION['cart'])) {
//   echo "<p>Your cart is empty.</p>";
// } else {
//   echo "<ul>";
//   foreach ($_SESSION['cart'] as $product_id => $product_details) {
//     echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
//   }
//   echo "</ul>";
// }

?>
