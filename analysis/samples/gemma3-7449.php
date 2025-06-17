        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php
// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to handle the purchase process
function handlePurchase($cart, $user_id) {
  $conn = connectToDatabase();

  // 1. Update the Cart (Reduce quantities)
  foreach ($cart as $product_id => $quantity) {
    // Check if the product exists
    $product_query = "SELECT id, quantity FROM products WHERE id = $product_id";
    $product_result = $conn->query($product_query);

    if ($product_result->num_rows > 0) {
      $product_data = $product_result->fetch_assoc();
      $new_quantity = $product_data['quantity'] - $quantity;

      // Check if enough stock
      if ($new_quantity >= 0) {
        // Update product quantity in the database
        $update_query = "UPDATE products SET quantity = $new_quantity WHERE id = $product_id";
        if ($conn->query($update_query) === TRUE) {
          echo "Product quantity updated successfully.";
        } else {
          echo "Error updating product quantity: " . $conn->error;
        }
      } else {
        echo "Not enough stock for product ID: " . $product_id;
      }
    } else {
      echo "Product ID: " . $product_id . " not found.";
    }
  }

  // 2. Create an Order Record
  $order_id = $conn->insert_id; // Get the last inserted ID (for simplicity)
  $order_date = date("Y-m-d H:i:s");
  $total_amount = calculateTotal($cart); // Call a function to calculate total

  $insert_order_query = "INSERT INTO orders (user_id, order_date, total_amount, order_id) VALUES ($user_id, '$order_date', $total_amount, $order_id)";
  if ($conn->query($insert_order_query) === TRUE) {
    echo "Order created successfully!";
  } else {
    echo "Error creating order: " . $conn->error;
  }

  // 3. Create Order Items (Link Products to Orders)
  foreach ($cart as $product_id => $quantity) {
    $insert_order_item_query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, $product_id, $quantity)";
    if ($conn->query($insert_order_item_query) === TRUE) {
      echo "Order item added successfully.";
    } else {
      echo "Error adding order item: " . $conn->error;
    }
  }

  // Close the connection
  $conn->close();
}

// Function to calculate the total order amount
function calculateTotal($cart) {
  $total = 0;
  foreach ($cart as $product_id => $quantity) {
    $product_query = "SELECT price FROM products WHERE id = $product_id";
    $product_result = $conn->query($product_result);
    if ($product_result->num_rows > 0) {
      $product_data = $product_result->fetch_assoc();
      $total += $product_data['price'] * $quantity;
    }
  }
  return $total;
}


// --- Example Usage (Simulated Cart) ---
$cart = [
  1 => 2,  // Product ID 1, quantity 2
  2 => 1,  // Product ID 2, quantity 1
  3 => 3   // Product ID 3, quantity 3
];

$user_id = 1; // Replace with the actual user ID

handlePurchase($cart, $user_id);

?>
