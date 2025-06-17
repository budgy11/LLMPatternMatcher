    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <button type="submit">Place Order</button>
  </form>

  <p>
    <a href="index.php">Back to Home</a>
  </p>

</body>
</html>


<?php

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase functionality
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
  // Validate inputs (add more robust validation here)
  if (empty($cart_id) || empty($customer_name) || empty($customer_email) || empty($payment_method)) {
    return "Error: All fields are required.";
  }

  //  Check if the cart exists
  $sql = "SELECT * FROM carts WHERE id = '$cart_id'";
  $result = $conn->query($sql);

  if (!$result->num_rows) {
    return "Error: Cart not found.";
  }

  $cart = $result->fetch_assoc();

  // Insert order information into the orders table
  $order_id = $conn->insert_id; // Get the last inserted ID
  $sql = "INSERT INTO orders (cart_id, customer_name, customer_email, order_date, payment_method, order_id)
          VALUES ('$cart_id', '$customer_name', '$customer_email', NOW(), '$payment_method', '$order_id')";

  if ($conn->query($sql) === TRUE) {
    // Update the cart to mark it as purchased
    $sql = "UPDATE carts SET purchased = 1, order_id = '$order_id' WHERE id = '$cart_id'";
    if ($conn->query($sql) === TRUE) {
      return "Purchase successful! Order ID: " . $order_id;
    } else {
      return "Error updating cart.";
    }
  } else {
    return "Error inserting order.";
  }
}

// -----  Example Usage (This would typically be handled by a form) -----

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data (replace with actual form input names)
  $cart_id = $_POST["cart_id"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];
  $payment_method = $_POST["payment_method"];

  // Process the purchase
  $purchaseResult = handlePurchase($cart_id, $customer_name, $customer_email, $payment_method);

  // Display the result
  echo "<p>" . $purchaseResult . "</p>";
}
?>
