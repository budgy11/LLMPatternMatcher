  <?php
  // Assume you have a way to get product IDs and names
  // This is a placeholder - adapt this part to your product listing
  // and your product data source.
  $products = [
    ['product_id' => 1, 'product_name' => 'Laptop'],
    ['product_id' => 2, 'product_name' => 'Mouse'],
    ['product_id' => 3, 'product_name' => 'Keyboard']
  ];

  foreach ($products as $product) {
    echo "<label for='product_" . $product['product_id'] . "'>Add " . $product['product_name'] . ":</label>";
    echo "<input type='number' id='product_" . $product['product_id'] . "' name='product_" . $product['product_id'] . "' value='1' min='1'>";
    echo "<br>";
  }
  ?>
