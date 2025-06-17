
    <?php
    $total = 0;
    foreach ($cart_items as $item) {
      echo '<div class="cart-item">';
      echo '<strong>' . $item['name'] . '</strong> - $' . $item['price'] . ' <input type="number" value="' . $item['quantity'] . '" min="1" style="width: 50px;">';
      echo '<br>';
      $total += $item['price'] * $item['quantity'];
      echo '</strong>';
      echo '<br>';
      echo '<a href="cart.php?remove_from_cart=' . $item['id'] . '">Remove</a><br><br>';
    }
    ?>
