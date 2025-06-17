
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

  <br>

  <form method="post" action="">
    <label for="remove_product_id">Remove Product:</label>
    <input type="number" id="remove_product_id" name="product_id" value="<?php echo $products[0]['id']; ?>" min="1">
