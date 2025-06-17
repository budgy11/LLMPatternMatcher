    </select>
    <br><br>

    <button type="submit">Place Order</button>
  </form>

  <p>Available Products:</p>
  <ul>
    <?php
    foreach ($products as $product) {
      echo "<li>$product[name] - $product[price]</li>";
    }
    ?>
