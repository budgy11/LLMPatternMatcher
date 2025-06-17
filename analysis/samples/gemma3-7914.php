      <label for="product_id">Product ID:</label>
      <input type="number" id="product_id" name="product_id" required>
      <br><br>

      <label for="quantity">Quantity:</label>
      <input type="number" id="quantity" name="quantity" value="1" min="1" required>
      <br><br>

      <label for="customer_name">Customer Name:</label>
      <input type="text" id="customer_name" name="customer_name" >
      <br><br>

      <label for="customer_email">Customer Email:</label>
      <input type="email" id="customer_email" name="customer_email" >
      <br><br>

      <button type="submit">Place Order</button>
    </form>
  </div>
</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$db_host = 'localhost';
$db_name = 'shop_db';
$db_user = 'your_user';
$db_password = 'your_password';

//  Assume a simple product table (you'll need to create this in your database)
//  Columns: id, product_name, price
//  Example data:  id=1, product_name='T-Shirt', price=20; id=2, product_name='Jeans', price=50;

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Function to display the product list
function displayProducts($pdo) {
    $stmt = $pdo->prepare("SELECT id, product_name, price FROM products");
    $stmt->execute();
    echo "<h2>Available Products</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>"; // Escape for security
        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Function to handle the purchase process
function processPurchase($product_id, $quantity, $pdo) {
    // Validate inputs (add more validation as needed)
    if (!is_numeric($product_id) || !is_numeric($quantity)) {
        return false;
    }
    $quantity = intval($quantity); // Ensure quantity is an integer

    // Check if product exists
    $stmt = $pdo->prepare("SELECT id, product_name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        return false;
    }

    // Calculate total price
    $total_price = $product['price'] * $quantity;

    // Add order to the database (you'll need an 'orders' table with columns: id, product_id, quantity, total_price, order_date)
    // Example:
    $order_date = date('Y-m-d H:i:s'); // Get current timestamp

    $stmt = $pdo->prepare("INSERT INTO orders (product_id, quantity, total_price, order_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$product_id, $quantity, $total_price, $order_date]);

    return true; // Purchase successful
}


// --- Main Script Execution ---

// 1. Display Products
displayProducts($pdo);

// 2. Handle Purchase Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (processPurchase($product_id, $quantity, $pdo)) {
        echo "<p style='color:green;'>Purchase successful! Your order ID is: " . $product_id . "</p>";
    } else {
        echo "<p style='color:red;'>Purchase failed.  Please check your inputs.</p>";
    }
}


?>
