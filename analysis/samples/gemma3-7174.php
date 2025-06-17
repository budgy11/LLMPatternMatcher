
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <h2>Add to Cart</h2>
  <form method="post">
    <select name="product_id" id="product_id">
      <?php
      // Example:  Fetch products from the 'products' table
      $product_sql = "SELECT product_id, product_name FROM products";
      $product_result = $db_connection->query($product_sql);

      if ($product_result->num_rows > 0) {
        while ($product = $product_result->fetch_assoc()) {
          echo "<option value='" . $product["product_id"] . "'>" . $product["product_name"] . "</option>";
        }
      }
      ?>
