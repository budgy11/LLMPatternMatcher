

<?php

// Database connection details (replace with your actual credentials)
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

// Function to add a new order to the database
function addOrder($orderData) {
  global $conn;

  // Sanitize and validate input data (IMPORTANT!)
  $product_id = $conn->real_escape_string($orderData['product_id']);
  $quantity = $conn->real_escape_string($orderData['quantity']);
  $customer_name = $conn->real_escape_string($orderData['customer_name']);
  $customer_email = $conn->real_escape_string($orderData['customer_email']);

  // Check if product exists - basic validation
  $product_query = "SELECT id FROM products WHERE id = '$product_id'";
  $product_result = $conn->query($product_query);
  if (!$product_result->num_rows) {
    return "Error: Product ID does not exist.";
  }


  $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email)
          VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email')";

  if ($conn->query($sql) === TRUE) {
    return "Order added successfully!";
  } else {
    return "Error: " . $conn->error;
  }
}


// Function to get product details (for display, etc.)
function getProductDetails($product_id) {
    global $conn;

    $product_query = "SELECT id, name, price FROM products WHERE id = '$product_id'";
    $product_result = $conn->query($product_query);

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        return $product;
    } else {
        return null; // Or handle the case where the product doesn't exist.
    }
}


// --- Example Usage - Handling the Purchase Form Submission ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  $customer_name = $_POST["customer_name"];
  $customer_email = $_POST["customer_email"];

  // Perform validation - VERY IMPORTANT!  Don't trust user input.
  if (!isset($product_id) || empty($product_id) || !is_numeric($product_id)) {
    $result = "Error: Invalid Product ID.";
  } elseif (!isset($quantity) || empty($quantity) || !is_numeric($quantity)) {
    $result = "Error: Invalid Quantity.";
  } else {
    // Add the order to the database
    $result = addOrder(["product_id" => $product_id, "quantity" => $quantity, "customer_name" => $customer_name, "customer_email" => $customer_email]);
  }
}

?>
