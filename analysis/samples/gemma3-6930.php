    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" min="1" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_db";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  return $conn;
}

// Function to handle purchase processing
function processPurchase($cart, $user_id) {
  $conn = connectToDatabase();

  // Create a temporary table to store the order details
  $sql = "CREATE TEMPORARY TABLE IF NOT EXISTS temp_order (
            order_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            total_amount DECIMAL(10, 2)
  )";
  if (!$conn->query($sql)) {
    echo "Error creating temporary table: " . $conn->error;
    return false;
  }

  // Insert order details into the temporary table
  $sql = "INSERT INTO temp_order (user_id, total_amount) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $user_id, calculateTotal($cart));
  if (!$stmt->execute()) {
    echo "Error inserting order details: " . $stmt->error;
    $stmt->close();
    return false;
  }
  $stmt->close();

  // Insert order items into the temporary table
  foreach ($cart as $item_id => $quantity) {
    $sql = "INSERT INTO temp_order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $conn->insert_id, $item_id, $quantity);
    if (!$stmt->execute()) {
      echo "Error inserting order item: " . $stmt->error;
      $stmt->close();
      $conn->rollback(); // Rollback the transaction on error
      return false;
    }
    $stmt->close();
  }

  // Commit the transaction
  $conn->commit();

  //  (Optional)  Send email confirmation, etc.

  //  (Optional)  Clean up the temporary table
  //  $conn->drop_temporary_table('temp_order'); // Use this if you're using MySQL 8.0+

  return true; // Purchase processed successfully
}


// Function to calculate the total order amount
function calculateTotal($cart) {
  $total = 0;
  foreach ($cart as $item_id => $quantity) {
    // Retrieve product price from the products table (replace with your query)
    $product = getProductDetails($item_id);
    if ($product) {
      $total += $product['price'] * $quantity;
    }
  }
  return $total;
}

//  Placeholder function to fetch product details.  Replace with your actual query.
function getProductDetails($product_id) {
  //  Replace this with your database query to fetch product details
  //  Example:
  //  $sql = "SELECT price FROM products WHERE id = ?";
  //  $stmt = $conn->prepare($sql);
  //  $stmt->bind_param("i", $product_id);
  //  $stmt->execute();
  //  $result = $stmt->get_result();
  //  if ($row = $result->fetch_assoc()) {
  //    return $row;
  //  }
  //  return null;

  // Dummy product
  if ($product_id == 1) {
    return ['price' => 10.00];
  }
  if ($product_id == 2) {
    return ['price' => 25.50];
  }
  return null;
}

// Example usage (simulated cart and user ID)
$cart = [
  1 => 2, // Product ID 1, quantity 2
  2 => 1  // Product ID 2, quantity 1
];
$user_id = 1;

if (processPurchase($cart, $user_id)) {
  echo "Purchase successful! Order ID: " . $conn->insert_id;
} else {
  echo "Purchase failed.";
}

// Close the database connection
$conn->close();

?>
