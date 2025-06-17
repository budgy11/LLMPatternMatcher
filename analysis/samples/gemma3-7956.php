
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace these with your actual database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ---  Purchase Functionality ---

/**
 * Processes a purchase request.
 *
 * @param array $order_data  An associative array containing order details.
 *                         Example: ['product_id' => 123, 'quantity' => 2, 'customer_name' => 'John Doe']
 * @return int|string  Returns the order ID on success, or an error message string on failure.
 */
function processPurchase(array $order_data) {
  // Validate input (Important for security!)
  if (empty($order_data['product_id']) || empty($order_data['quantity']) || empty($order_data['customer_name'])) {
    return "Error: Missing order details.";
  }

  if (!is_numeric($order_data['product_id']) || $order_data['product_id'] <= 0) {
    return "Error: Invalid product ID.";
  }

  if (!is_numeric($order_data['quantity']) || $order_data['quantity'] <= 0) {
    return "Error: Invalid quantity.";
  }

  // Sanitize customer name (Prevent SQL injection)
  $customer_name = $conn->real_escape_string($order_data['customer_name']);

  // Prepare the SQL query
  $sql = "INSERT INTO orders (product_id, quantity, customer_name, order_date)
          VALUES ($order_data['product_id'], $order_data['quantity'], '$customer_name', NOW())";

  if ($conn->query($sql) === TRUE) {
    // Optionally, you can update inventory here
    // Example:  update product_stock set stock = stock - $order_data['quantity'] WHERE product_id = $order_data['product_id'];

    return $conn->insert_id; // Returns the newly inserted order ID
  } else {
    return "Error: " . $conn->error;
  }
}


// --- Example Usage ---

// Simulate a new order
$new_order_data = [
  'product_id' => 1, // Assuming product ID 1 exists in your database
  'quantity' => 3,
  'customer_name' => 'Alice Smith'
];

// Process the purchase
$order_id = processPurchase($new_order_data);

// Display the result
if ($order_id === NULL) {  // Checking for null indicates an error
  echo "Purchase failed: " . $order_id;
} else {
  echo "Purchase successful! Order ID: " . $order_id;
}


// --- Database Setup (Example - Create Tables) ---
//  This part is just for demonstration and setting up the database.
//  You'll likely have your database already set up.

// This code won't execute unless you've created the tables!
// Make sure you run this only once to create the tables.
// It's commented out to avoid accidentally executing it multiple times.

/*
$sql_create_orders = "CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  customer_name VARCHAR(255) NOT NULL,
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_create_orders) === TRUE) {
  echo "Table 'orders' created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

*/

?>
