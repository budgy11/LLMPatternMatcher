
    <button type="submit">Add to Cart</button>
  </form>

  <h2>Your Cart</h2>
  <?php
    // Display Cart Items
    if (isset($_SESSION['cart_items'])) {
      echo "<ul>";
      foreach ($_SESSION['cart_items'] as $item) {
        echo "<li>Product: " . $item['product_name'] . ", Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>Your cart is empty.</p>";
    }
  ?>
