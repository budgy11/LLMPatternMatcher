
  <hr>

  <h2>Add to Cart</h2>
  <?php if (empty($cart_items)) {
        echo "<form method='post'>
                  <label for='product_id'>Product ID:</label>
                  <input type='number' id='product_id' name='product_id' required>
                  <label for='quantity'>Quantity:</label>
                  <input type='number' id='quantity' name='quantity' value='1' min='1'>
                  <button type='submit' name='add_to_cart'>Add to Cart</button>
                </form>";
    }
    ?>
