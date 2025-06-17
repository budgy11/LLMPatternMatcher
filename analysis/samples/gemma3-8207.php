
  <?php
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<h2>Cart Items:</h2>";
    foreach ($cart as $item) {
      echo "<div class='cart-item'>";
      echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " ";
      echo "Quantity: " . $item['quantity'];
      echo "<form method='post' action='cart.php'>";
      echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
      echo "<input type='submit' value='Update Quantity'>";
      echo "</form>";
      echo "<form method='post' action='cart.php'>";
      echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
      echo "<input type='submit' value='Remove from Cart'>";
      echo "</form>";
      echo "</div>";
    }
    echo "<div class='cart-total'>Total: $" . $cart_total . "</div>";
  }
  ?>
