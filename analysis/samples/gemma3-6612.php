

<?php
session_start();

// Example: Add a product to the cart

// Product details
$product_id = 123;
$product_name = "Awesome T-Shirt";
$product_price = 25.00;

// Check if the cart exists
if (!isset($_SESSION['cart'])) {
  // If the cart doesn't exist, initialize it as an empty array
  $_SESSION['cart'] = array();
}

// Check if the product is already in the cart
if (isset($_SESSION['cart'][$product_id])) {
  // Product is already in the cart - increment quantity
  $_SESSION['cart'][$product_id]['quantity']++;
} else {
  // Product is not in the cart - add it with quantity 1
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $product_price,
    'quantity' => 1
  );
}

// Output the contents of the cart
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_details) {
    echo "<li>" . $item_details['name'] . " - $" . $item_details['price'] . " x " . $item_details['quantity'] . " = $" . ($item_details['price'] * $item_details['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate total cart value
  $total = 0;
  foreach ($_SESSION['cart'] as $item_id => $item_details) {
    $total += $item_details['price'] * $item_details['quantity'];
  }
  echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";
}
?>
