

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// -------------------------------------------------------------------
//  Purchase Functionality - Main Logic
// -------------------------------------------------------------------

/**
 * Processes a purchase order.
 *
 * @param array $orderData The data for the order (product_id, quantity, user_id, etc.)
 * @return bool|string Returns true on success, or an error message string on failure.
 */
function processPurchase(array $orderData) {
  // 1. Validate Input - Important security step!
  if (empty($orderData['product_id']) || empty($orderData['quantity']) || empty($orderData['user_id'])) {
    return "Error: Missing required order details.";
  }

  // Validate product_id and quantity (ensure they are integers and positive)
  if (!is_numeric($orderData['product_id']) || $orderData['product_id'] <= 0) {
    return "Error: Invalid product_id.";
  }

  if (!is_numeric($orderData['quantity']) || $orderData['quantity'] <= 0) {
    return "Error: Invalid quantity.";
  }

  // 2. Database Interaction - Use prepared statements for security
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION); // Important for error handling
  } catch (PDOException $e) {
    return "Error: Database connection failed: " . $e->getMessage();
  }

  // Prepare SQL statement
  $sql = "INSERT INTO orders (product_id, quantity, user_id, order_date) VALUES (:product_id, :quantity, :user_id, NOW())";
  $stmt = $pdo->prepare($sql);

  // Bind parameters
  $stmt->bindParam(':product_id', $orderData['product_id']);
  $stmt->bindParam(':quantity', $orderData['quantity']);
  $stmt->bindParam(':user_id', $orderData['user_id']);

  // Execute the statement
  if ($stmt->execute()) {
    //  Optionally: Update inventory (if you have an inventory table)
    // Example:
    // $inventoryUpdateSql = "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :product_id";
    // $inventoryStmt = $pdo->prepare($inventoryUpdateSql);
    // $inventoryStmt->bindParam(':quantity', $orderData['quantity']);
    // $inventoryStmt->bindParam(':product_id', $orderData['product_id']);
    // $inventoryStmt->execute();


    return true; // Success
  } else {
    return "Error: Failed to insert order.  " . print_r($stmt->errorInfo(), true);  // Debugging
  }
}


// -------------------------------------------------------------------
//  Example Usage - Simulate a Purchase Request
// -------------------------------------------------------------------

// Example Order Data (in a real application, this would come from a form)
$orderData = [
    'product_id' => 123,
    'quantity' => 2,
    'user_id' => 456
];

// Process the purchase
$result = processPurchase($orderData);

if ($result === true) {
  echo "Purchase successful!  Order ID: " . md5("Order Generated - " . json_encode($orderData)); // Use md5 for a simple unique identifier.  Never store passwords unencrypted.
} else {
  echo "Purchase failed: " . $result; // Display the error message
}

?>
