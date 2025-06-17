    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Processes a purchase request.
 *
 * @param array $cartData An associative array containing the items in the cart 
 *                       (e.g., ['product_id' => 123, 'quantity' => 2]).
 * @return string The purchase status message.
 */
function processPurchase(array $cartData) {
  // 1. Validate Cart Data
  if (empty($cartData)) {
    return "Error: Cart is empty.";
  }

  // 2. Simulate Order Creation (Replace with your actual order logic)
  $order_id = bin2hex(random_bytes(16)); // Generate a unique order ID
  $total_amount = calculateTotal($cartData);

  // 3. Create Order in Database (Simulated)
  $sql = "INSERT INTO orders (order_id, order_date, total_amount) VALUES (?, NOW(), ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $order_id, $total_amount);
  if (!$stmt->execute()) {
    return "Error creating order: " . $conn->error;
  }
  $stmt->close();

  // 4. Create Order Items (Simulated)
  $sql_items = "INSERT INTO order_items (order_id, product_id, quantity) ";
  $sql_items .= "VALUES (?, ?, ?)";
  $stmt_items = $conn->prepare($sql_items);
  foreach ($cartData as $product_id => $quantity) {
    $stmt_items->bind_param("sis", $order_id, $product_id, $quantity);
    if (!$stmt_items->execute()) {
      return "Error creating order item: " . $conn->error;
    }
  }
  $stmt_items->close();



  // 5. Update Cart (Simulate - Remove items from the cart)
  // In a real application, you'd update the cart table.
  // This example just returns a success message.

  return "Purchase successful! Order ID: " . $order_id;
}


/**
 * Calculates the total amount of the order.
 *
 * @param array $cartData The cart data.
 * @return float The total amount.
 */
function calculateTotal(array $cartData) {
  $total = 0;
  foreach ($cartData as $product_id => $quantity) {
    // Replace this with your actual product price lookup
    $product_price = getProductPrice($product_id); 
    $total += $product_price * $quantity;
  }
  return round($total, 2);
}

/**
 * Retrieves the price of a product.  (Replace with your actual database lookup)
 *
 * @param int $product_id The product ID.
 * @return float The product price.
 */
function getProductPrice(int $product_id) {
  //  Replace with your actual database query to get product price
  // This is just an example.
  if ($product_id == 123) {
    return 25.00;
  } elseif ($product_id == 456) {
    return 10.50;
  } else {
    return 0.00;  //Default price if not found.
  }
}



// --- Example Usage ---

// Sample cart data
$cart = [
  123 => 2,  // Product ID 123, quantity 2
  456 => 1   // Product ID 456, quantity 1
];

// Process the purchase
$purchase_result = processPurchase($cart);
echo $purchase_result;  // Output the purchase status
?>
