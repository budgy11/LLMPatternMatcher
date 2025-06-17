

<?php

session_start();

// ---------------------------------------------------------------------
// Example Product Data (Replace with your actual product data)
// ---------------------------------------------------------------------

$products = [
  1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  3 => ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];


// ---------------------------------------------------------------------
// Add to Cart Function
// ---------------------------------------------------------------------

function addToCart($product_id, $quantity = 1)
{
  global $products;

  if (isset($products[$product_id])) {
    if (isset($_SESSION['cart'])) {
      $_SESSION['cart'][$product_id] += $quantity;
    } else {
      $_SESSION['cart'] = [
        $product_id => $quantity
      ];
    }
  } else {
    // Product not found - you might want to handle this differently
    echo "Product ID " . $product_id . " not found.";
  }
}


// ---------------------------------------------------------------------
// Example Usage:  Adding items to the cart
// ---------------------------------------------------------------------

// Add one T-shirt
addToCart(1);

// Add two pairs of Jeans
addToCart(2, 2);

// Add one pair of Shoes
addToCart(3);


// ---------------------------------------------------------------------
// Display Cart Contents
// ---------------------------------------------------------------------

echo "<h2>Your Shopping Cart</h2>";

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = $products[$product_id];
    echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
  }
  echo "</ul>";

  // Calculate total
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = $products[$product_id];
    $total += $product['price'] * $quantity;
  }
  echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";

} else {
  echo "<p>Your cart is empty.</p>";
}

// ---------------------------------------------------------------------
//  Example: Removing an item from the cart (optional)
// ---------------------------------------------------------------------
//
//  You would need a function like this:
//  function removeFromCart($product_id) {
//    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
//      unset($_SESSION['cart'][$product_id]);
//      if (count($_SESSION['cart']) == 0) {
//        unset($_SESSION['cart']); // Clear the entire cart if it's empty
//      }
//    }
//  }
//
//  // Example: Remove one T-shirt
//  // removeFromCart(1);
//
?>
