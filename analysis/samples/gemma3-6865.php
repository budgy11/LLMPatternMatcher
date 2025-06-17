    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <button type="submit">Add to Cart</button>
  </form>

  <h2>Your Cart</h2>
  <?php
  // Display cart contents
  if (isset($_SESSION["cart"])) {
    echo "<ul>";
    foreach ($_SESSION["cart"] as $item) {
      echo "<li>" . $item["name"] . " - Quantity: " . $item["quantity"] . " - Price: $" . $item["price"] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>Your cart is empty.</p>";
  }
  ?>
