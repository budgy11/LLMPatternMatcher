

<?php

// Database Connection (Replace with your database credentials)
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

// --- Functions ---

/**
 * Adds a new order to the database.
 *
 * @param array $orderData  An associative array containing order details.
 *                          Example: ['product_id' => 123, 'quantity' => 2, 'customer_id' => 456]
 * @return int|false  The ID of the new order if successful, or false on failure.
 */
function addOrder(array $orderData) {
  global $conn;

  $product_id = $orderData['product_id'];
  $quantity = $orderData['quantity'];
  $customer_id = $orderData['customer_id'];

  // Sanitize inputs (important for security!) -  This is a basic example.  Expand as needed.
  $product_id = (int)$product_id;
  $quantity = (int)$quantity;
  $customer_id = (int)$customer_id;

  // SQL Query
  $sql = "INSERT INTO orders (product_id, quantity, customer_id) VALUES (?, ?, ?)";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("iii", $product_id, $quantity, $customer_id);

    // Execute statement
    if ($stmt->execute()) {
      // Get the last inserted ID
      $order_id = $conn->insert_id;
      return $order_id; // Return the actual ID inserted
    } else {
      error_log("Query failed: " . $conn->error);
      return false;
    }
  } else {
    error_log("Prepare failed: " . $conn->error);
    return false;
  }

  // Close statement
  $stmt->close();
}

/**
 * Retrieves product details by product_id.
 *
 * @param int $product_id The ID of the product to retrieve.
 * @return array|null An associative array containing product details, or null if not found.
 */
function getProductDetails(int $product_id) {
  $sql = "SELECT * FROM products WHERE id = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row;
    } else {
      return null;
    }

    $stmt->close();
  } else {
    error_log("Prepare failed: " . $conn->error);
    return null;
  }
}

// --- Example Usage (Handle form submission or other triggering event) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  $customer_id = $_POST["customer_id"];

  // Validate input (Important!  Don't just trust user input.)
  if (!is_numeric($product_id) || $product_id <= 0) {
    $error = "Invalid product ID.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error = "Invalid quantity.";
  } elseif (!is_numeric($customer_id) || $customer_id <= 0) {
    $error = "Invalid customer ID.";
  } else {

    // Add the order to the database
    $order_id = addOrder(["product_id" => $product_id, "quantity" => $quantity, "customer_id" => $customer_id]);

    if ($order_id) {
      $message = "Order placed successfully! Order ID: " . $order_id;
    } else {
      $error = "Failed to place order. Please try again.";
    }
  }
}
?>
