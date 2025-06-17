    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Purchase</button>
</form>

<a href="index.php">Back to Home</a> <!-- Or wherever your home page is -->

</body>
</html>


<?php
  echo "<h1>Welcome to the Store</h1>";
  echo "<p>Click <a href='purchase.php'>here</a> to make a purchase.</p>";
?>
