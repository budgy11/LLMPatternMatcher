

<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase logic
function handlePurchase($product_id, $quantity, $customer_name, $customer_email) {
  // Validate input (basic example - you should add more robust validation)
  if (empty($product_id) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
    return "Error: All fields are required.";
  }

  if (!is_numeric($quantity) || $quantity <= 0) {
    return "Error: Quantity must be a positive number.";
  }

  // Fetch product details from the database
  $sql = "SELECT product_id, product_name, price FROM products WHERE product_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $product_id); // "i" for integer
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return "Error: Product not found.";
  }

  $product = $result->fetch_assoc();

  // Calculate total price
  $total_price = $product['price'] * $quantity;

  //  Simulate order processing (replace with actual database interaction)
  //  In a real application, you would insert a new record into the 'orders' table.
  echo "<h2>Order Summary:</h2>";
  echo "<p>Product: " . $product['product_name'] . "</p>";
  echo "<p>Quantity: " . $quantity . "</p>";
  echo "<p>Total Price: $" . number_format($total_price, 2) . "</p>"; // Format to 2 decimal places

  echo "<p>Customer Name: " . $customer_name . "</p>";
  echo "<p>Customer Email: " . $customer_email . "</p>";
  
  return "Order placed successfully! (Simulated)"; // Return a confirmation message
}

// ---  Handling the Purchase Request (Example - Using a POST request) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];

  $order_confirmation = handlePurchase($product_id, $quantity, $customer_name, $customer_email);

  echo $order_confirmation;
} else {
  // If not a POST request, display a form to collect purchase details.
  ?>
