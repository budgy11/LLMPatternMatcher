</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_user";
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
function processPurchase($cart_id, $user_id) {
  $conn = connectToDatabase();

  // 1.  Fetch products from the cart based on cart_id
  $sql = "SELECT * FROM cart_items WHERE cart_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $cart_id);  // 'i' for integer
  $stmt->execute();
  $result = $stmt->get_result();

  $cart_items = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
  }

  // 2. Check if cart is empty
  if (empty($cart_items)) {
    $conn->close();
    return "Cart is empty.  Cannot process purchase.";
  }


  // 3.  Update order table
  $order_id = generateOrderID(); //Implement this function (see below)
  $sql = "INSERT INTO orders (order_id, user_id, order_date) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iii", $order_id, $user_id, date("Y-m-d H:i:s"));
  $stmt->execute();

  // 4. Insert order items into order_items table
  foreach ($cart_items as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $order_item_id = generateOrderItemID();  // Implement this function (see below)

    $sql = "INSERT INTO order_items (order_item_id, order_id, product_id, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $order_item_id, $order_id, $product_id, $quantity);
    $stmt->execute();
  }

  // 5. Update the cart (mark items as sold)
  foreach ($cart_items as $item) {
    $product_id = $item['product_id'];

    $sql = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $item['quantity'], $product_id);
    $stmt->execute();
  }

  // 6.  Clean up the cart (delete cart items)
  $sql = "DELETE FROM cart_items WHERE cart_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $cart_id);
  $stmt->execute();
  $stmt->close();

  // 7. Close the connection
  $conn->close();

  return "Purchase successful! Order ID: " . $order_id;
}

// Function to generate a unique order ID (example - you can use a more robust method)
function generateOrderID() {
  return rand(100000, 999999);  // Simple example - consider using UUIDs for production.
}

// Function to generate a unique order item ID
function generateOrderItemID() {
  return rand(100000, 999999);
}

// --- Example Usage (This would usually be triggered by a form submission) ---

// Assuming $cart_id and $user_id are passed from a form

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $cart_id = $_POST["cart_id"];
  $user_id = $_POST["user_id"];

  // Validate the inputs (important!) - you should use more robust validation.
  if (!is_numeric($cart_id) || !is_numeric($user_id)) {
    $result = "Invalid cart_id or user_id.  Both must be numeric.";
  } else {
    $result = processPurchase($cart_id, $user_id);
  }
} else {
  $result = "No purchase initiated.";
}

?>
