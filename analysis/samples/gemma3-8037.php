

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Session handling to maintain shopping cart data
session_start();

// --- Helper Functions ---

// Function to connect to the database
function connect_db($host, $username, $password, $dbname) {
  $conn = new mysqli($host, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to get the total cart value
function calculateTotal($conn) {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
      // Fetch product details from the database
      $product_query = "SELECT price, quantity FROM products WHERE id = " . $product_id;
      $result = $conn->query($product_query);
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total += $row['price'] * $item['quantity'];
      }
    }
  }
  return $total;
}

// --- Purchase Functionality ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate form data (Add more validation as needed)
  if (empty($_POST['name']) || empty($_POST['address']) || empty($_POST['payment_method'])) {
    $name_error = "Name is required.";
    $address_error = "Address is required.";
    $payment_error = "Payment method is required.";
  } else {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];
  }


  // Process the purchase
  $conn = connect_db($host, $username, $password, $dbname);

  // Clear the cart after successful purchase
  $_SESSION['cart'] = array();

  // You'll likely want to log this purchase in a database table
  // For now, we'll just display a success message

  echo "<div class='success'>Order placed successfully!  Thank you, " . $name . "</div>";
}


// --- Product Display (Example - Replace with your actual product data) ---
$product_data = array(
  array('id' => 1, 'name' => 'T-Shirt', 'price' => 20),
  array('id' => 2, 'name' => 'Jeans', 'price' => 50),
  array('id' => 3, 'name' => 'Hat', 'price' => 15)
);


?>
