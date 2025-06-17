    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php
session_start(); // Start the session

// --- Database Connection (Replace with your actual connection details) ---
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Helper Functions ---

// Function to sanitize input (important for security)
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// --- Purchase Functionality ---

// Check if the purchase form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize form data
  $product_id = sanitizeInput($_POST["product_id"]);
  $quantity = (int)sanitizeInput($_POST["quantity"]); // Cast to integer
  $shipping_address = sanitizeInput($_POST["shipping_address"]);
  $payment_method = sanitizeInput($_POST["payment_method"]);

  // --- Error Handling ---
  $errors = [];

  if (!is_numeric($product_id)) {
    $errors[] = "Invalid product ID.";
  }
  if ($quantity <= 0) {
    $errors[] = "Quantity must be greater than zero.";
  }

  if (empty($shipping_address)) {
    $errors[] = "Shipping address is required.";
  }

  if (empty($payment_method)) {
      $errors[] = "Payment method is required.";
  }


  // --- Process Purchase if no errors ---
  if (empty($errors)) {
    // --- Database Interaction ---
    // 1. Get Product Information (Replace with your product table structure)
    $sql_product = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt_product = $conn->prepare($sql_product);
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $result = $stmt_product->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
      die("Product not found.");
    }

    // 2. Update Inventory (Decrease Quantity)
    $sql_update_inventory = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update_inventory);
    $stmt_update->bind_param("id", $quantity, $product_id);
    $stmt_update->execute();

    if ($stmt_update->affected_rows == 0) {
        die("Failed to update inventory.");
    }

    // 3.  Store Order Information (This is a simplified example -  You'll likely want
    //     to use a more robust order table and potentially a billing/shipping table)
    $sql_insert_order = "INSERT INTO orders (product_id, quantity, shipping_address, payment_method) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert_order);
    $stmt_insert->bind_param("isss", $product_id, $quantity, $shipping_address, $payment_method);
    $stmt_insert->execute();

    // 4. Success Message
    $order_id = $conn->insert_id; // Get the auto-incremented ID of the new order.
    echo "<p>Purchase successful! Order ID: " . $order_id . "</p>";
    echo "<p>Product: " . $product["name"] . "</p>";
    echo "<p>Quantity: " . $quantity . "</p>";
    echo "<p>Shipping Address: " . $shipping_address . "</p>";
    echo "<p>Payment Method: " . $payment_method . "</p>";

    // 5.  (Optional) Clear Cart -  You might want to reset the cart after a purchase.
    // unset($_SESSION['cart']);

  } else {
    // Display Errors
    echo "<p>Errors: " . implode(", ", $errors) . "</p>";
  }
}
?>
