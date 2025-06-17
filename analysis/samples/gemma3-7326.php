

<?php

// Database connection details
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ---  Purchase Functionality ---

/**
 * Processes a purchase order.
 *
 * @param array $orderData An associative array containing the order details.
 *                         Expected keys:  product_id, quantity, customer_name, customer_email
 * @return string  A success or error message.
 */
function processPurchase(array $orderData) {
  // Validate input data - crucial for security
  if (empty($orderData['product_id']) || empty($orderData['quantity']) || empty($orderData['customer_name']) || empty($orderData['customer_email'])) {
    return "Error: Missing required fields.";
  }
  $product_id = trim($orderData['product_id']);
  $quantity = (int)$orderData['quantity']; // Convert to integer
  $customer_name = trim($orderData['customer_name']);
  $customer_email = trim($orderData['customer_email']);

  if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  //  ---  Database Interaction  ---
  $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, order_date) VALUES (" .
         $conn->real_escape_string($product_id) . ", " .
         $conn->real_escape_string($quantity) . ", '" .
         $conn->real_escape_string($customer_name) . "', '" .
         $conn->real_escape_string($customer_email) . "', NOW())";

  if ($conn->query($sql) === TRUE) {
    // Optionally, update the product quantity in the products table
    // Example:
    // $update_sql = "UPDATE products SET stock_quantity = stock_quantity - " . $quantity . " WHERE product_id = " . $conn->real_escape_string($product_id);
    // if ($conn->query($update_sql) === TRUE) {
    //   return "Order placed successfully! Order ID: " . $conn->insert_id;
    // } else {
    //   return "Error updating product quantity.";
    // }

    return "Order placed successfully! Order ID: " . $conn->insert_id;
  } else {
    return "Error: " . $conn->error;
  }
}


// ---  Example Usage (Simulated Form Submission) ---

// Let's assume this is a form submission
$order_data = [
  'product_id' => '101',
  'quantity' => 2,
  'customer_name' => 'John Doe',
  'customer_email' => 'john.doe@example.com'
];

$result = processPurchase($order_data);
echo $result;

// ---  Database Setup (Example - create tables if they don't exist) ---
// You should run this *once* to create the tables.  Don't re-run it on every request.
// You can also use a database migration tool for more robust changes.

// This code creates a very basic database setup for demonstration.
// DO NOT USE THIS IN A PRODUCTION ENVIRONMENT WITHOUT CAREFUL REVIEW AND ADJUSTMENT!
//  It's here just to allow the example code to run without a pre-existing database.
//  Consider using a migration tool or a database schema management system.


$sql_create_products = "CREATE TABLE IF NOT EXISTS products (
  product_id INT PRIMARY KEY,
  product_name VARCHAR(255),
  stock_quantity INT DEFAULT 0
)";

if (!$conn->query($sql_create_products)) {
  die("Error creating products table: " . $conn->error);
}

$sql_create_orders = "CREATE TABLE IF NOT EXISTS orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT,
  quantity INT,
  customer_name VARCHAR(255),
  customer_email VARCHAR(255),
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql_create_orders)) {
    die("Error creating orders table: " . $conn->error);
}



?>
