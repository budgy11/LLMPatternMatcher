
</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_db";
$db_user = "your_username";
$db_pass = "your_password";

// Function to connect to the database
function connectToDatabase() {
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit; // Stop execution if database connection fails
  }
}

// Function to handle the purchase process
function handlePurchase($cart, $customer_name, $customer_email) {
  $pdo = connectToDatabase();

  // Insert order information into the 'orders' table
  try {
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, order_date) VALUES (:customer_name, :customer_email, NOW())");
    $stmt->bindParam(':customer_name', $customer_name);
    $stmt->bindParam(':customer_email', $customer_email);
    $stmt->execute();
    $order_id = $pdo->lastInsertId(); // Get the ID of the newly created order

    // Insert order items into the 'order_items' table
    foreach ($cart as $item_id => $quantity) {
      $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
      $stmt->bindParam(':order_id', $order_id);
      $stmt->bindParam(':product_id', $item_id);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->execute();
    }

    return $order_id; // Return the order ID
  } catch (PDOException $e) {
    echo "Error processing purchase: " . $e->getMessage();
    return -1; // Return -1 to indicate an error
  }
}

// Example usage (Simulated form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get cart data from the form (Simulate form data)
  $cart = $_POST["cart"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];

  // Handle the purchase
  $order_id = handlePurchase($cart, $customer_name, $customer_email);

  if ($order_id > 0) {
    echo "<h3>Purchase successful!</h3>";
    echo "<p>Order ID: " . $order_id . "</p>";
    echo "<p>Thank you for your order!</p>";
  } else {
    echo "<h3>Purchase failed. Please try again.</h3>";
  }
}
?>
