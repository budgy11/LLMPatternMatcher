

<?php
session_start();

// Simulate a product
$product_name = "Awesome T-Shirt";
$product_price = 25.00;

// Function to add to cart
function addToCart($product_name, $product_price, $quantity = 1) {
  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the product to the cart
  $_SESSION['cart'][] = array(
    'name' => $product_name,
    'price' => $product_price,
    'quantity' => $quantity
  );

  // Optional: Update quantity if product already in cart
  $product_found = false;
  for ($i = 0; $i < count($_SESSION['cart']); $i++) {
    if ($_SESSION['cart'][$i]['name'] == $product_name) {
      $_SESSION['cart'][$i]['quantity'] += $quantity;
      $product_found = true;
      break;
    }
  }

  // If the product wasn't found, add it to the cart
  if (!$product_found) {
    $_SESSION['cart'][] = array(
      'name' => $product_name,
      'price' => $product_price,
      'quantity' => $quantity
    );
  }

  // Optional: Calculate total cart value
  $_SESSION['cart_total'] = 0;
  foreach ($_SESSION['cart'] as $item) {
    $_SESSION['cart_total'] += $item['price'] * $item['quantity'];
  }
}

// Example Usage:
// Add one T-Shirt to the cart
addToCart($product_name, $product_price);

// Add two more T-Shirts
addToCart($product_name, $product_price, 2);

// Display the cart contents (for demonstration)
echo "<h2>Your Cart:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . number_format($_SESSION['cart_total'], 2) . "</strong></p>";
}

?>
