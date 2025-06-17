    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
      <option value="1">Laptop</option>
      <option value="2">Mouse</option>
      <option value="3">Keyboard</option>
    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1"><br><br>

    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

  <h2>Your Cart</h2>
  <?php if (!empty($cart_items)): ?>
