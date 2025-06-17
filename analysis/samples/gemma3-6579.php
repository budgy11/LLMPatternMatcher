
  <br>
  <a href="index.php">Back to Products</a>  <!-- Link back to the product listing page -->

</body>
</html>


<?php

session_start();

// --- Function to Add to Cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Add the product to the cart
  $_SESSION['cart'][$product_id] = [
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// --- Example Usage (Simulating a product) ---
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 80]
];

// 1. User adds a T-Shirt
addToCart(1, 'T-Shirt', $products[1]['price']);

// 2. User adds two Jeans
addToCart(2, 'Jeans', $products[2]['price'], 2);

// 3. User adds one pair of Shoes
addToCart(3, 'Shoes', $products[3]['price']);

// --- Display the Cart ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>
