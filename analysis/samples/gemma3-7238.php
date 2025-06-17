    <br><br>

    <label for="product_id">Product ID:</label>
    <select id="product_id" name="product_id">
      <?php
      foreach ($products as $product) {
        echo "<option value=\"$product[id]\"";
        if ($product_id == $product['id']) {
          echo " selected";
        }
        echo ">$product[name]</option>";
      }
      ?>
