    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <input type="submit" value="Purchase">
  </form>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
  try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}

// Function to handle the purchase process
function processPurchase($cart, $customerName, $customerEmail, $address, $paymentAmount) {
  $pdo = connectToDatabase();

  try {
    // 1. Insert Order Information
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, address, order_date, total_amount) VALUES (:customer_name, :customer_email, :address, NOW(), :total_amount)");
    $stmt->bindParam(':customer_name', $customerName);
    $stmt->bindParam(':customer_email', $customerEmail);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':total_amount', $paymentAmount);
    $stmt->execute();
    $orderId = $pdo->lastInsertId();

    // 2. Insert Order Items
    foreach ($cart as $item) {
      $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
      $stmt->bindParam(':order_id', $orderId);
      $stmt->bindParam(':product_id', $item['product_id']);
      $stmt->bindParam(':quantity', $item['quantity']);
      $stmt->execute();
    }

    // 3.  Update Stock (If you have stock management)
    //  You'll need to implement this based on how you manage inventory.
    //  Example (this is a basic example - adjust to your needs):
    // foreach ($cart as $item) {
    //   // Find the product by product_id
    //   $product = $pdo->prepare("SELECT id, stock FROM products WHERE id = :product_id");
    //   $productResult = $productResult->execute(['product_id' => $item['product_id']]);
    //   $product = $productResult->fetch(PDO::FETCH_ASSOC);
    //   $product['stock'] -= $item['quantity'];
    //   // Update the product stock
    //   $updateStmt = $pdo->prepare("UPDATE products SET stock = :stock WHERE id = :product_id");
    //   $updateStmt->bindParam(':stock', $product['stock']);
    //   $updateStmt->bindParam(':product_id', $product['id']);
    //   $updateStmt->execute();

    // }


    return $orderId;  // Return the order ID
  } catch (PDOException $e) {
    die("Database error during purchase: " . $e->getMessage());
  }
}



// ---  Purchase Form Handling (Simplified -  adapt for your form) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 1.  Retrieve Cart Data (Assumed to be in a session or form)
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
  } else {
    $cart = []; // Initialize an empty cart
  }


  $customerName = $_POST["customer_name"];
  $customerEmail = $_POST["customer_email"];
  $address = $_POST["address"];
  $paymentAmount = floatval($_POST["payment_amount"]);  // Ensure it's a float


  // 2. Process the Purchase
  $orderId = processPurchase($cart, $customerName, $customerEmail, $address, $paymentAmount);

  // 3.  Clear the Cart After Purchase
  session_start();
  unset($_SESSION['cart']);
  session_writeClose();

  // 4. Display Confirmation Message
  echo "<p>Order placed successfully! Order ID: " . $orderId . "</p>";
}
?>
