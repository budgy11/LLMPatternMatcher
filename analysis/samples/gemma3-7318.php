
</body>
</html>


<?php

// This is a simplified example.  A real-world e-commerce application would be much more complex.
// This example demonstrates the basic structure and concepts.

// Data (simulated database)
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20, 'stock' => 10],
  2 => ['name' => 'Jeans', 'price' => 50, 'stock' => 5],
  3 => ['name' => 'Hat', 'price' => 15, 'stock' => 20]
];

$cart = []; // Array to hold the items in the customer's cart

// Function to display the products
function displayProducts($products) {
  echo "<h2>Available Products</h2>";
  echo "<ul>";
  foreach ($products as $id => $product) {
    echo "<li>";
    echo "<h3>" . $product['name'] . "</h3>";
    echo "Price: $" . $product['price'] . "<br>";
    echo "Stock: " . $product['stock'] . "<br>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $id . "'>";
    echo "<button type='submit'>Add to Cart</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
}

// Function to add a product to the cart
function addToCart($product_id, $cart) {
  if (isset($products[$product_id])) {
    if ($products[$product_id]['stock'] > 0) {
      $product = $products[$product_id];
      $cart[] = $product;
      $product['stock']--; // Update stock
      echo "<p>Added '" . $product['name'] . "' to cart.</p>";
    } else {
      echo "<p>Sorry, this product is out of stock.</p>";
    }
  } else {
    echo "<p>Product ID not found.</p>";
  }
}

// Function to display the cart
function displayCart($cart) {
  echo "<h2>Your Cart</h2>";
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart as $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    // Calculate total price
    $totalPrice = 0;
    foreach ($cart as $item) {
      $totalPrice += $item['price'];
    }
    echo "<p><strong>Total Price: $" . $totalPrice . "</strong></p>";
  }
}

// Handle form submission (add to cart)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['product_id'])) {
    addToCart($_POST['product_id'], $cart);
  }
}

// Display the page
?>
