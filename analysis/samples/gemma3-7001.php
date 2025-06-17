    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="product_price">Product Price:</label>
    <input type="number" id="product_price" name="product_price" step="0.01" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <button type="submit">Add to Purchase</button>
  </form>

  <a href="index.php">View Purchases</a> <!-- Link to a page displaying the purchases -->

</body>
</html>


<?php
// Assuming you have a "purchases" table
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM purchases";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<h2>Purchases:</h2>";
  while($row = $result->fetch_assoc()) {
    echo "<p><strong>Product:</strong> " . $row["product_name"] . "<br>";
    echo "<strong>Price:</strong> $" . $row["product_price"] . "<br>";
    echo "<strong>Quantity:</strong> " . $row["quantity"] . "</p>";
  }
} else {
  echo "<p>No purchases found.</p>";
}

$conn->close();
?>
