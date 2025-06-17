    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <?php
      foreach ($products as $id => $item) {
        echo "<option value='" . $id . "'>" . $item['name'] . "</option>";
      }
      ?>
