    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email"><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" max="100" value="1"><br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" step="0.01" min="0.01" value="10.00"><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Database connection (replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
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

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $shipping_address, $payment_method) {
  $conn = connectToDatabase();

  // 1. Validate Input (Important for security)
  if (empty($cart_id) || empty($customer_name) || empty($customer_email) || empty($shipping_address) || empty($payment_method)) {
    return "Error: All fields are required.";
  }
  
  // Sanitize inputs to prevent SQL injection
  $cart_id = $conn->real_escape_string($cart_id); 

  // 2. Get Cart Details
  $query = "SELECT product_id, quantity, price FROM cart WHERE cart_id = '$cart_id'";
  $result = $conn->query($query);

  if (!$result) {
    return "Error retrieving cart details.";
  }

  $cart_items = [];
  while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
  }

  if (empty($cart_items)) {
    return "Error: Cart is empty.";
  }

  // 3. Calculate Total Amount
  $total_amount = 0;
  foreach ($cart_items as $item) {
    $total_amount += $item['quantity'] * $item['price'];
  }

  // 4. Insert Order into Database
  $query = "INSERT INTO orders (cart_id, customer_name, customer_email, shipping_address, total_amount, order_date) 
            VALUES ('$cart_id', '$customer_name', '$customer_email', '$shipping_address', '$total_amount', NOW())";
  if (!$conn->query($query)) {
    return "Error inserting order: " . $conn->error;
  }

  // 5. Update Cart (Mark items as purchased -  This is crucial!)
  for ($i = 0; $i < count($cart_items); $i++) {
    $product_id = $cart_items[$i]['product_id'];
    $quantity = $cart_items[$i]['quantity'];

    $update_query = "UPDATE cart SET quantity = 0 WHERE product_id = '$product_id'";
    if (!$conn->query($update_query)) {
      return "Error updating cart: " . $conn->error;
    }
  }

  return "Order placed successfully! Order ID: " . $conn->insert_id;
}


// --- Example Usage (Handle Purchase Request) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $cart_id = $_POST["cart_id"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];
  $shipping_address = $_POST["shipping_address"];
  $payment_method = $_POST["payment_method"];

  $purchase_result = handlePurchase($cart_id, $customer_name, $customer_email, $shipping_address, $payment_method);
  echo $purchase_result;
}

?>
