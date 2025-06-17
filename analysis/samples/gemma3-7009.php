<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>My Online Store</h1>

  <h2>Products</h2>
  <!-- Replace this with your product listing logic -->
  <p>Product 1 - $10</p>
  <p>Product 2 - $20</p>
  <p>Product 3 - $30</p>

  <h2>Add to Cart</h2>
  <form action="add_to_cart.php" method="POST">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <button type="submit">Add to Cart</button>
  </form>

  <?php display_cart(); ?>
