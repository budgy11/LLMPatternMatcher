
  <hr>

  <h2>Add Items to Cart</h2>
  <?php
  if (empty($cart_items)) {
      echo "<p>Click <a href='add_to_cart.php?product_id=1'>here</a> to add a T-Shirt.</p>";
      echo "<p>Click <a href='add_to_cart.php?product_id=2'>here</a> to add Jeans.</p>";
      echo "<p>Click <a href='add_to_cart.php?product_id=3'>here</a> to add Shoes.</p>";
  }
  ?>
