
</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

// Start the session
session_start();

// Function to connect to the database
function connectToDatabase() {
  try {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    $conn->set_charset("utf8"); // Set character set for proper handling of special characters
    return $conn;
  } catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
  }
}


// Function to handle the purchase process
function processPurchase($order_data) {
  $conn = connectToDatabase();

  if (!$conn) {
    return false;
  }

  // Sanitize input - VERY IMPORTANT to prevent SQL injection
  $product_id = mysqli_real_escape_string($conn, $order_data['product_id']);
  $quantity = mysqli_real_escape_string($conn, $order_data['quantity']);
  $customer_name = mysqli_real_escape_string($conn, $order_data['customer_name']);
  $customer_email = mysqli_real_escape_string($conn, $order_data['customer_email']);

  // Prepare the SQL query
  $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, order_date) 
          VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email', NOW())";

  // Execute the query
  $result = $conn->query($sql);

  if ($result) {
    // Optionally update product stock (requires product_id and quantity in your product table)
    // Example:
    // $update_sql = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
    // $update_result = $conn->query($update_sql);
    // if (!$update_result) {
    //    error_log("Failed to update product stock: " . $conn->error);
    // }

    // Display success message
    echo "<p>Order placed successfully! Order ID: " . $conn->insert_id . "</p>";
    
    // You could also redirect the user to a confirmation page
    // header("Location: confirmation.php");
    // exit;
    
  } else {
    echo "<p>Error placing order: " . $conn->error . "</p>";
  }
  $conn->close();
}

// ---  Handling the form submission (e.g., from a purchase form)  ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the form has been submitted
  if (isset($POST['submit'])) {
    // Validate and sanitize input (important!)
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);

    // Check if product_id is set - add more validation as needed
    if (empty($product_id) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
      echo "<p>Please fill in all the fields.</p>";
    } else {
      // Process the order
      processPurchase($_POST);
    }
  }
}
?>
