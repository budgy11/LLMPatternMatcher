

<?php

// Database Connection (Replace with your actual database details)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPassword = "your_db_password";
$dbName = "your_db_name";

// Establish Database Connection
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Processes a purchase.
 *
 * @param array $orderData An associative array containing order information:
 *                       - product_id (int): ID of the product purchased.
 *                       - quantity (int): Quantity of the product purchased.
 * @return bool True on success, false on failure.
 */
function processPurchase($orderData) {
  // Validate Input (Very important!)
  if (!is_array($orderData) || empty($orderData)) {
    return false; // Invalid input
  }

  if (!isset($orderData['product_id']) || !is_numeric($orderData['product_id']) || $orderData['product_id'] <= 0) {
    return false; // Invalid product_id
  }

  if (!isset($orderData['quantity']) || !is_numeric($orderData['quantity']) || $orderData['quantity'] <= 0) {
    return false; // Invalid quantity
  }

  // Sanitize Input (Important for security)
  $product_id = (int)$orderData['product_id'];
  $quantity = (int)$orderData['quantity'];

  // --- Database Query ---
  $sql = "INSERT INTO orders (product_id, quantity, order_date) VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    error_log("Prepare statement failed: " . $conn->error);
    return false;
  }

  $stmt->bind_param("ii", $product_id, $quantity);

  if (!$stmt->execute()) {
    error_log("Query failed: " . $stmt->error);
    $stmt->close();
    return false;
  }

  $stmt->close();

  // --- Handle Product Update (Optional - Depending on your needs) ---
  // You might want to update the product's stock level here
  // Example:
  // $sql_update = "UPDATE products SET stock = stock - ? WHERE id = ?";
  // $stmt_update = $conn->prepare($sql_update);
  // $stmt_update->bind_param("ii", $quantity, $product_id);
  // if (!$stmt_update->execute()) {
  //   error_log("Update product stock failed: " . $stmt_update->error);
  // }
  // $stmt_update->close();


  return true;
}



// --- Example Usage (Illustrative - You'll need to integrate this into your form) ---

// Example Order Data (From a form submission)
$orderData = [
  'product_id' => 1,  // Example Product ID
  'quantity' => 2
];

if (processPurchase($orderData)) {
  echo "Order placed successfully!";
} else {
  echo "Order placement failed. Please check your input and try again.";
}

// --- Close Database Connection ---
$conn->close();

?>
