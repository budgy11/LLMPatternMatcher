  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<!-- HTML form to update the quantity of items -->
<form method="post" action="">
  <h2>Update Quantity</h2>
  <?php
  foreach ($products as $id => $product) {
    echo "<label for='" . $id . "'>" . $product['name'] . "</label><br>";
    echo "<input type='number' id='" . $id . "' name='product_id' value='" . $id . "' min='1' max='10'><br>"; // Added min and max
  }
  ?>
