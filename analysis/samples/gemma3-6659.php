  </select>
  <br><br>
  <label for="quantity">Quantity:</label>
  <input type="number" name="quantity" value="1" min="1">
  <br><br>
  <input type="submit" name="add_to_cart" value="Add to Cart">
</form>

<!-- Example HTML to simulate removing from cart -->
<form method="post" action="">
    <label for="product_id">Product ID to Remove:</label>
    <select name="product_id" id="product_id">
        <?php
        foreach ($products as $id => $product) {
            echo "<option value='" . $id . "'>" . $id . "</option>";
        }
        ?>
