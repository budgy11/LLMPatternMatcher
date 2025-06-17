

<?php

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPassword = "your_password";
$dbName = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  return $conn;
}

// Function to handle the purchase request
function handlePurchase($cart, $customerName, $customerEmail, $paymentMethod) {
  $conn = connectToDatabase();

  // Prepare the SQL query
  $sql = "INSERT INTO orders (customer_name, customer_email, order_date, payment_method) 
          VALUES ('" . $customerName . "', '" . $customerEmail . "', NOW(), '" . $paymentMethod . "')";

  if ($conn->query($sql) === TRUE) {
    $orderId = $conn->insert_id; // Get the ID of the newly inserted order
    echo "New order created successfully! Order ID: " . $orderId . "<br>";

    // Add order items to the order table
    foreach ($cart as $item) {
      $product_id = $item['product_id'];
      $quantity = $item['quantity'];

      $product_sql = "INSERT INTO order_items (order_id, product_id, quantity)
                       VALUES ('" . $orderId . "', '" . $product_id . "', '" . $quantity . "')";

      if ($conn->query($product_sql) === TRUE) {
        echo "  - Product ID " . $product_id . " added to order.<br>";
      } else {
        echo "  Error adding product ID " . $product_id . " to order.  Error: " . $conn->error . "<br>";
      }
    }

  } else {
    echo "Error creating order: " . $conn->error . "<br>";
  }

  $conn->close();
}

// --- Example Usage (Simulated Form Handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $cart = array(); // Initialize the cart as an empty array

  // Simulate getting cart data from a form
  // In a real application, this data would come from your form
  $cart_items = $_POST['cart_items'];
  if ($cart_items) {
    foreach ($cart_items as $item) {
      $product_id = $item['product_id'];
      $quantity = $item['quantity'];
      $cart[] = array('product_id' => $product_id, 'quantity' => $quantity);
    }
  }


  $customerName = $_POST["customer_name"];
  $customerEmail = $_POST["customer_email"];
  $paymentMethod = $_POST["payment_method"];

  // Handle the purchase
  handlePurchase($cart, $customerName, $customerEmail, $paymentMethod);
}

?>
