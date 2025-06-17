    <br><br>

    <button type="submit">Place Order</button>
  </form>

  <br><br>

  <h2>Order History</h2>
  <?php
    // Fetch order history (example - adapt to your database structure)
    $order_query = "SELECT id, name, email, quantity, order_date FROM orders";
    $result = $conn->query($order_query);

    if ($result->num_rows > 0) {
      echo "<h3>Order History:</h3>";
      while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<strong>Order ID:</strong> " . $row["id"] . "<br>";
        echo "<strong>Name:</strong> " . $row["name"] . "<br>";
        echo "<strong>Email:</strong> " . $row["email"] . "<br>";
        echo "<strong>Quantity:</strong> " . $row["quantity"] . "<br>";
        echo "<strong>Order Date:</strong> " . $row["order_date"] . "<br>";
        echo "</div>";
      }
    } else {
      echo "<p>No orders found.</p>";
    }

  ?>
