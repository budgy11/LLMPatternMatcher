    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <button type="submit">Place Order</button>
  </form>

  <?php
    if (isset($result)) {
      echo "<p style='color: red;'>$result</p>";
    }
  ?>
