

<?php

// Database connection details (Replace with your actual details)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate form data (Basic validation - expand for production)
  $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
  $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
  $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
  $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

  // Database query (Replace 'products' with your table name and adjust columns)
  $sql = "INSERT INTO orders (name, email, product_id, quantity) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssii", $name, $product_id, $quantity);

  if ($stmt->execute()) {
    $success = true;
    $message = "Order placed successfully! Order ID: " . $conn->insert_id; //Get the auto-increment id.
  } else {
    $success = false;
    $message = "Error placing order. " . $stmt->error;
  }

  // Close the statement
  $stmt->close();

} else {
  // If the form wasn't submitted, initialize variables
  $success = false;
  $message = "";
}


// --- Example Product Information (For demonstration - replace with your product data) ---
$products = [
  ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  ['id' => 2, 'name' => 'Mug', 'price' => 10],
  ['id' => 3, 'name' => 'Hat', 'price' => 15]
];


// ---  Display Form (HTML) ---
?>
