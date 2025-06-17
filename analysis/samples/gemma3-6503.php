  <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

<!-- Form to remove items -->
<form method="post" action="">
   <h2>Remove from Cart</h2>
   <?php
   foreach ($products as $product_id => $product) {
      echo "<label for='" . $product_id . "'>" . $product['name'] . "</label><br>";
      echo "<input type='submit' name='remove_from_cart' value='Remove ' . $product['name']  />";
   }
   ?>
