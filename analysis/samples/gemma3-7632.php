    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <input type="submit" value="Place Order">
  </form>

  <p>Existing Orders:</p>
  <?php
  //  Example: Display existing orders (replace with your database query)
  //  This is just a placeholder, connect to your database and query.
  $sql = "SELECT * FROM orders";
  $result = mysqli_query($conn, $sql); // Replace $conn with your database connection

  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<p>Order ID: " . $row["order_id"] . ", Product: " . $row["product_name"] . ", Quantity: " . $row["quantity"] . ", Price: " . $row["price"] . "</p>";
    }
  } else {
    echo "<p>No orders found.</p>";
  }
  ?>
