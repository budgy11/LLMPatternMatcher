    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Add to Cart</button>
  </form>

  <?php
    if (isset($error)) {
      echo "<p style='color: red;'>Error: " . $error . "</p>";
    }
    if (isset($success)) {
      echo "<p style='color: green;'>Success: " . $success . "</p>";
    }

    displayCart(); // Display the cart contents
  ?>
