        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        <input type="submit" value="Purchase">
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce_db";
$db_user = "your_username";
$db_password = "your_password";

// Handle the purchase process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 1. Validation & Sanitization (Crucial for security!)
  $product_id = isset($_POST["product_id"]) ? filter_input(INPUT_POST, "product_id", FILTER_SANITIZE_NUMBER_INT) : null;
  $quantity = isset($_POST["quantity"]) ? filter_input(INPUT_POST, "quantity", FILTER_SANITIZE_NUMBER_INT) : 1; // Default quantity is 1
  $customer_name = isset($_POST["customer_name"]) ? filter_input(INPUT_POST, "customer_name", FILTER_SANITIZE_STRING) : "";
  $customer_email = isset($_POST["customer_email"]) ? filter_input(INPUT_POST, "customer_email", FILTER_SANITIZE_EMAIL) : "";

  // Basic validation
  if (!$product_id || $product_id <= 0 || !$quantity || $quantity <= 0) {
    $error = "Invalid product ID or quantity.";
  } elseif (empty($customer_name) && empty($customer_email)) {
      $error = "Please provide your name and/or email address.";
  }
  
  // 2. Database Interaction
  if (!$error) {
    try {
      // Connect to the database
      $pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name . ";charset=utf8mb4", $db_user, $db_password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION); // Throw exceptions on errors
  
      // Get product details
      $stmt = $pdo->prepare("SELECT product_id, product_name, price FROM products WHERE product_id = :id");
      $stmt->bindParam(':id', $product_id);
      $stmt->execute();
      $product = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if (!$product) {
        throw new Exception("Product not found.");
      }
  
      // Calculate total price
      $total_price = $product["price"] * $quantity;
  
      // Insert order details into the orders table
      $sql = "INSERT INTO orders (customer_name, customer_email, product_id, quantity, total_price, order_date) VALUES (:name, :email, :product_id, :quantity, :total_price, NOW())";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $customer_name);
      $stmt->bindParam(':email', $customer_email);
      $stmt->bindParam(':product_id', $product_id);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':total_price', $total_price);
      $stmt->execute();
  
      // You might want to update inventory here, depending on your requirements.
  
      // Success message
      $success = "Order placed successfully! Order ID: " . $pdo->lastInsertId();
  
    } catch (PDOException $e) {
      // Error handling
      $error = "Database error: " . $e->getMessage();
    }
  }
}
?>
