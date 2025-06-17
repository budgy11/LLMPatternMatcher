
</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
  $conn = connectToDatabase();

  // Check if the cart exists
  $check_cart_query = "SELECT * FROM carts WHERE id = '$cart_id'";
  $cart_result = $conn->query($check_cart_query);

  if (!$cart_result->num_rows) {
    echo "Error: Cart not found.";
    return false;
  }

  // Get cart details
  $cart_data = $cart_result->fetch_assoc();

  // Update the order table
  $order_total = $cart_data['total_price'];
  $order_date = date("Y-m-d H:i:s");

  $insert_order_query = "INSERT INTO orders (cart_id, customer_name, customer_email, order_total, order_date, payment_method)
                         VALUES ('$cart_id', '$customer_name', '$customer_email', '$order_total', '$order_date', '$payment_method')";

  if (!$conn->query($insert_order_query)) {
    echo "Error creating order: " . $conn->error;
    return false;
  }

  // Clear the cart after successful order
  $clear_cart_query = "DELETE FROM carts WHERE id = '$cart_id'";
  if (!$conn->query($clear_cart_query)) {
    echo "Error clearing cart: " . $conn->error;
    return false;
  }

  echo "Order placed successfully! Order ID: " . $conn->insert_id;
  return true;
}


// --- Example Usage (Simulated HTML form and handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate inputs (VERY IMPORTANT!)
  $cart_id = $_POST["cart_id"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];
  $payment_method = $_POST["payment_method"];

  // Call the handlePurchase function
  if (handlePurchase($cart_id, $customer_name, $customer_email, $payment_method)) {
    // Order placed successfully, redirect or display a success message
    // echo "<p>Order placed successfully!</p>";
    // header("Location: confirmation.php"); // Redirect to a confirmation page
  } else {
    // Handle errors from handlePurchase
    echo "<p style='color:red;'>Error placing order. Please try again later.</p>";
  }
}
?>
