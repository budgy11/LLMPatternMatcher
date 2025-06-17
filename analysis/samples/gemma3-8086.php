

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Processes a purchase request.
 *
 * @param array $orderData An array containing the order information.
 *                       Example: ['product_id' => 123, 'quantity' => 2, 'customer_name' => 'John Doe']
 * @return array An array containing the response data (success/failure, order ID, etc.).
 */
function processPurchase(array $orderData) {
  // Validate input (basic example - enhance for production)
  if (empty($orderData['product_id']) || empty($orderData['quantity']) || empty($orderData['customer_name'])) {
    return ['success' => false, 'message' => 'Missing order information.'];
  }

  // Product data (simulated for example purposes)
  $products = [
    123 => ['name' => 'Laptop', 'price' => 1200],
    456 => ['name' => 'Mouse', 'price' => 25],
    789 => ['name' => 'Keyboard', 'price' => 75]
  ];

  // Get product details
  $product = $products[$orderData['product_id']];

  // Calculate total price
  $totalPrice = $product['price'] * $orderData['quantity'];

  // Generate a unique order ID (simple example)
  $orderId = bin2hex(random_bytes(8));

  // Insert order into the database (using prepared statements for security)
  $sql = "INSERT INTO orders (order_id, product_id, quantity, customer_name, total_price)
          VALUES (?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("issss", $orderId, $orderData['product_id'], $orderData['quantity'], $orderData['customer_name'], $totalPrice);
  $stmt->execute();

  // Check if the insertion was successful
  if ($stmt->affected_rows > 0) {
    //  Optionally, you'd likely create a new order item table for more granular tracking
    //  For this example, we'll just return the order ID
    return ['success' => true, 'order_id' => $orderId];
  } else {
    return ['success' => false, 'message' => 'Failed to insert order into database.'];
  }

  $stmt->close();
}


// --- Example Usage (Simulated form submission) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $orderData = [
    'product_id' => isset($_POST['product_id']) ? intval($_POST['product_id']) : null,
    'quantity' => isset($_POST['quantity']) ? intval($_POST['quantity']) : 1,
    'customer_name' => isset($_POST['customer_name']) ? $_POST['customer_name'] : 'Guest'
  ];

  if (is_array($orderData) && !empty($orderData)) {
    $purchaseResult = processPurchase($orderData);

    if ($purchaseResult['success']) {
      echo "<h2>Purchase Successful!</h2>";
      echo "<p>Order ID: " . $purchaseResult['order_id'] . "</p>";
    } else {
      echo "<h2>Purchase Failed!</h2>";
      echo "<p>Error: " . $purchaseResult['message'] . "</p>";
    }
  } else {
    echo "<p>Invalid order data received.</p>";
  }
}
?>
