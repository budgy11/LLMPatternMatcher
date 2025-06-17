
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Purchase Functionality</h1>

  <form method="post" action="">
    <label for="cart_id">Cart ID:</label>
    <input type="text" id="cart_id" name="cart_id" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
      <option value="credit_card">Credit Card</option>
      <option value="paypal">PayPal</option>
      <option value="other">Other</option>
    </select><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// ... (Database connection code as before) ...

// Function to handle purchase functionality using prepared statements
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
  // Validate inputs
  if (empty($cart_id) || empty($customer_name) || empty($customer_email) || empty($payment_method)) {
    return "Error: All fields are required.";
  }

  // Prepare the SQL query using prepared statements
  $sql = "INSERT INTO orders (cart_id, customer_name, customer_email, order_date, payment_method, order_id)
          VALUES (?, ?, NOW(), ?, ?, ?)";

  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error); // Handle statement preparation errors
  }

  // Bind parameters to the statement
  $stmt->bind_param("siss", $cart_id, $customer_name, $payment_method);  // 's' for string

  // Execute the statement
  if ($stmt->execute() === false) {
    echo "Error executing statement: " . $stmt->error;  // Better error logging
    $stmt->close();
    return "Error: " . $stmt->error;
  }

  // Get the last inserted ID
  $order_id = $conn->insert_id;

  // Update the cart
  $sql = "UPDATE carts SET purchased = 1, order_id = '$order_id' WHERE id = '$cart_id'";
  if ($conn->query($sql) === TRUE) {
    return "Purchase successful! Order ID: " . $order_id;
  } else {
    return "Error updating cart.";
  }

  $stmt->close(); // Close the statement
}

// ... (Rest of the code remains the same) ...
?>
