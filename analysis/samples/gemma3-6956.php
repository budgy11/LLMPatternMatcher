

<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "my_shop";
$db_user = "root";
$db_pass = "password";

// Function to connect to the database
function connectDB() {
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to create a purchase
function createPurchase($product_id, $quantity, $customer_name, $customer_email) {
  $conn = connectDB();

  // Sanitize input (VERY IMPORTANT - DO THIS PROPERLY IN PRODUCTION)
  $product_id = (int)$product_id;  // Convert to integer
  $quantity = (int)$quantity;
  $customer_name = $conn->real_escape_string($customer_name);
  $customer_email = $conn->real_escape_string($customer_email);

  // Construct the SQL query (Use prepared statements in production!)
  $query = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email, purchase_date) VALUES ($product_id, $quantity, '$customer_name', '$customer_email', NOW())";

  if ($conn->query($query)) {
    return true;
  } else {
    return false;
  }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];

  if (createPurchase($product_id, $quantity, $customer_name, $customer_email)) {
    echo "Purchase created successfully!";
  } else {
    echo "Error creating purchase.";
  }
}
?>
