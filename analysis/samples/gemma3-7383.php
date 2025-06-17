
  <h2>Product Details (Example):</h2>
  <?php
  // Example of getting product details (for demonstration)
  $example_product_id = "1"; // Replace with a valid product ID
  $product = getProductDetails($example_product_id);

  if ($product) {
    echo "<p>Product ID: " . $product['id'] . "</p>";
    echo "<p>Product Name: " . $product['name'] . "</p>";
    echo "<p>Price: $" . $product['price'] . "</p>";
  } else {
    echo "<p>Product not found.</p>";
  }
  ?>
