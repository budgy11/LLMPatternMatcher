
</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
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

// Function to handle product purchase
function purchaseProduct($productId, $quantity, $user_id) {
  // 1. Validate Inputs
  if (!is_numeric($productId) || !is_numeric($quantity) || !is_numeric($user_id)) {
    return "Invalid input. Please ensure product ID, quantity, and user ID are numbers.";
  }

  if ($quantity <= 0) {
    return "Invalid quantity. Quantity must be greater than zero.";
  }

  // 2. Retrieve Product Details
  $sql = "SELECT id, name, price FROM products WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $productId); // 'i' indicates integer
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return "Product not found.";
  }
  $product = $result->fetch_assoc();

  // 3. Calculate Total Price
  $totalPrice = $product['price'] * $quantity;

  // 4. Update Inventory (Decrease Product Quantity)
  $update_sql = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
  $update_stmt = $conn->prepare($update_sql);
  $update_stmt->bind_param("is", $quantity, $productId);
  if ($update_stmt->execute() === false) {
    return "Error updating inventory. " . $conn->error;
  }


  // 5.  Create Purchase Record (Add to Order Table - You need to define this table)
  //  (Example table structure:  order_id (INT, AUTO_INCREMENT), user_id (INT), product_id (INT), quantity (INT), order_date (DATETIME), total_price (DECIMAL))
  $order_sql = "INSERT INTO orders (user_id, product_id, quantity, order_date, total_price)
                VALUES (?, ?, ?, NOW(), ?)"; // NOW() gets the current timestamp
  $insert_stmt = $conn->prepare($order_sql);
  $insert_stmt->bind_param("isd", $user_id, $productId, $quantity, $totalPrice);
  if ($insert_stmt->execute() === false) {
    return "Error creating order record. " . $conn->error;
  }

  // 6. Return Success Message
  return "Purchase successful! Order ID: " . $conn->insert_id; // insert_id gets the auto-incremented order ID
}


// ----- Example Usage (Form Handling) -----

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $productId = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  $user_id = $_POST["user_id"];  // Assuming you have a user ID (e.g., from login)

  $result = purchaseProduct($productId, $quantity, $user_id);

  echo "<p>" . $result . "</p>";
}

?>
