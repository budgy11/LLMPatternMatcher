    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name" placeholder="Enter product name" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required>
    <br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" min="0.01" required>
    <br><br>

    <button type="submit">Add to Purchase</button>
  </form>

  <br><br>

  <h2>Purchase History</h2>
  <?php
    // Retrieve purchase data from the database
    $sql = "SELECT product_name, quantity, price FROM purchases";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
      echo "<ul>";
      while($row = mysqli_fetch_assoc($result)) {
        echo "<li>Product: " . $row["product_name"] . ", Quantity: " . $row["quantity"] . ", Price: $" . $row["price"] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "No purchases yet.";
    }
  ?>
