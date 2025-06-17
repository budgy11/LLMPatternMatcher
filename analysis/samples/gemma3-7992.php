    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <input type="submit" value="Place Order">
  </form>
<?php
}

// --- Database Setup (For demonstration purposes - Create a sample 'products' table ---
/*
$sql = "CREATE TABLE IF NOT EXISTS products (
  product_id INT PRIMARY KEY,
  product_name VARCHAR(255) NOT NULL,
  price DECIMAL(10, 2) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
  $conn->query("INSERT INTO products (product_id, product_name, price) VALUES (1, 'Laptop', 1200.00)");
  $conn->query("INSERT INTO products (product_id, product_name, price) VALUES (2, 'Mouse', 25.00)");
} else {
  echo "Error creating table: " . $conn->error;
}
*/
?>
