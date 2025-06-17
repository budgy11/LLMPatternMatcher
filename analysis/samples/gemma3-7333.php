

<?php
// products.php

// Same products array as in cart.php
$products = [
  1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
  2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
  3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

echo '<h2>Available Products</h2>';
echo '<ul>';
foreach ($products as $id => $product) {
  echo '<li>';
  echo "Product: " . $product['name'] . "<br>";
  echo "Price: $" . $product['price'] . "<br>";
  echo "<form method=\"post\" action=\"cart.php\">"; // Link to cart.php
  echo "<input type=\"hidden\" name=\"product_id\" value=\"" . $id . "\">";
  echo "<button type=\"submit\" name=\"action\" value=\"add_to_cart\">Add to Cart</button>";
  echo "</li>";
}
echo '</ul>';
?>
