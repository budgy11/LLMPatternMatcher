    </select>
    <input type="number" name="quantity" value="1" min="1">
    <input type="submit" name="add_to_cart" value="Add to Cart">
  </form>

  <?php
  // Display the cart contents (for testing)
  $cart_items = getCartContents($db_connection);
  if (!empty($cart_items)) {
    echo "<h2>Cart</h2>";
    echo "<ul>";
    foreach ($cart_items as $item) {
      echo "<li>Product ID: " . $item["product_id"] . ", Quantity: " . $item["quantity"] . "</li>";
    }
    echo "</ul>";
  }
  ?>
