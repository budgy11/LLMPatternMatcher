  <?php
  // Assuming you have a way to display product IDs from the cart
  $cart_product_ids = array_keys($_SESSION['cart']);  // Get product IDs from cart
  if ($cart_product_ids) {
    foreach ($cart_product_ids as $product_id) {
      echo "<label for='remove_product_" . $product_id . "'>Remove " . $product_id . ":</label>";
      echo "<input type='submit' name='remove_product' value='Remove' class='remove_product' id='remove_product_" . $product_id . "'>";
    }
  }
  ?>
