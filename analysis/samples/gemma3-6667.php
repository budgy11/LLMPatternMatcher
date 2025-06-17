

<?php

session_start();

// Define a function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the product to the cart
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  // You can optionally store the total of the cart here for display purposes
  // $_SESSION['cart_total'] = calculateCartTotal($_SESSION['cart']);
}

// Function to calculate the total cart value
function calculateCartTotal($cart) {
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Example Usage ---

// Simulate a product ID, name, and price
$product_id = 1;
$product_name = "Awesome T-Shirt";
$price = 25.00;

// Add the item to the cart
addToCart($product_id, $product_name, $price);

// Add another quantity of the same item
addToCart($product_id, $product_name, $price, 2);

// Display the contents of the cart
if (isset($_SESSION['cart'])) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $product_info) {
    echo "<li>";
    echo "<strong>" . $product_info['name'] . "</strong> - $" . $product_info['price'] . " x " . $product_info['quantity'] . " = $" . ($product_info['price'] * $product_info['quantity']) . "</li>";
  }
  echo "</ul>";

  // Display the total
  echo "<p><strong>Total: $" . calculateCartTotal($_SESSION['cart']) . "</strong></p>";
} else {
  echo "<p>Your cart is empty.</p>";
}
?>
