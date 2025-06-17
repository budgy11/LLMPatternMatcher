    </select>
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

  <?php
  // Display Cart Contents
  $cart_items = getCartItems();

  if (!empty($cart_items)) {
    echo "<h2>Cart Items</h2>";
    echo "<ul>";
    foreach ($cart_items as $id => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>Your cart is empty.</p>";
  }
  ?>
