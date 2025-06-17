    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate input
  $product_id = isset($_POST["product_id"]) ? (int)$_POST["product_id"] : 0; // Integer conversion for safety
  $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 1; // Default quantity is 1
  $shipping_address = isset($_POST["shipping_address"]) ? trim($_POST["shipping_address"]) : "";
  $customer_name = isset($_POST["customer_name"]) ? trim($_POST["customer_name"]) : "";
  $customer_email = isset($_POST["customer_email"]) ? trim($_POST["customer_email"]) : "";

  // Validate input (Add more robust validation as needed)
  if ($product_id <= 0 || $quantity <= 0 || empty($shipping_address) || empty($customer_name) || empty($customer_email)) {
    $error = "Please fill in all fields correctly.";
  } else {
    // Database query (Replace with your product table and product details)
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);  //Use prepared statements to prevent SQL injection
    $stmt->bind_param("i", $product_id); // "i" indicates integer type
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $error = "Product not found.";
    } else {
      $product = $result->fetch_assoc();

      // Calculate total price
      $total_price = $product["price"] * $quantity;

      // Data for order creation
      $order_id = rand(100000, 999999); // Generate a random order ID
      $order_date = date("Y-m-d H:i:s");

      // Prepare data for insertion into the orders table
      $order_data = [
        "order_id" => $order_id,
        "customer_name" => $customer_name,
        "customer_email" => $customer_email,
        "shipping_address" => $shipping_address,
        "product_id" => $product_id,
        "quantity" => $quantity,
        "total_price" => $total_price,
        "order_date" => $order_date,
        "status" => "pending" // Initial order status
      ];

      // Insert order into the orders table
      $order_query = "INSERT INTO orders (order_id, customer_name, customer_email, shipping_address, product_id, quantity, total_price, order_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt_order = $conn->prepare($order_query);
      $stmt_order->bind_param("sisssisss", $order_id, $customer_name, $customer_email, $shipping_address, $product_id, $quantity, $total_price, $order_date, $status);
      $stmt_order->execute();

      // Insert order items into the order_items table
      $order_items_query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
      $stmt_item = $conn->prepare($order_items_query);
      $stmt_item->bind_param("iii", $order_id, $product_id, $quantity);
      $stmt_item->execute();

      // Display success message
      $success = "Order placed successfully! Order ID: " . $order_id;
    }
  }
}

//  Example HTML Form
?>
