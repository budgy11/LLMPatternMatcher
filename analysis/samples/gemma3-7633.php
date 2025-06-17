
</body>
</html>


<?php

// Database Connection (Replace with your database credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Processes a purchase.
 *
 * @param array $order_data  Array containing order details (e.g., product_id, quantity, user_id).
 * @return int|string  The order ID on success, or an error message on failure.
 */
function processPurchase(array $order_data) {
  // Validate Input - Crucial for security!
  if (empty($order_data['product_id']) || empty($order_data['quantity']) || empty($order_data['user_id'])) {
    return "Error: Missing order details.";
  }

  // Validate product_id and quantity
  $product_id = (int)$order_data['product_id'];
  $quantity = (int)$order_data['quantity'];

  if ($quantity <= 0) {
    return "Error: Invalid quantity.";
  }

  // Check if the product exists
  $product_query = "SELECT id, name, price FROM products WHERE id = $product_id";
  $product_result = $conn->query($product_query);

  if (!$product_result->num_rows) {
    return "Error: Product not found.";
  }

  $product = $product_result->fetch_assoc();

  // Calculate the total price
  $total_price = $product['price'] * $quantity;

  // Prepare the SQL statement
  $sql = "INSERT INTO orders (product_id, quantity, user_id, total_price, order_date)
          VALUES ($product_id, $quantity, $order_data['user_id'], $total_price, NOW())";

  // Execute the SQL statement
  if ($conn->query($sql) === TRUE) {
    // Optionally, you can update inventory here
    // Example:
    // $update_inventory_query = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
    // $conn->query($update_inventory_query);
    return $conn->insert_id; // Return the newly created order ID
  } else {
    return "Error: " . $conn->error;
  }
}



// --- Example Usage ---

// Simulate receiving order data (e.g., from a form submission)
$order = [
  'product_id' => 1,
  'quantity' => 2,
  'user_id' => 123  // Replace with the actual user ID
];

// Process the purchase
$order_id = processPurchase($order);

// Display the result
if ($order_id) {
  echo "Purchase successful! Order ID: " . $order_id;
} else {
  echo "Purchase failed: " . $order_id;
}

// Close the database connection (important!)
$conn->close();

?>
