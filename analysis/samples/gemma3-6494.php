
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <h2>Add to Cart</h2>
  <form method="post" action="cart.php">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <?php
      foreach ($products as $id => $product) {
        echo "<option value=\"$id\">$id - $product['name']</option>";
      }
      ?>
