
</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase creation
function createPurchase($cart_id, $customer_name, $customer_email, $payment_method) {
  global $conn; // Access the database connection

  // Sanitize input to prevent SQL injection
  $customer_name = $conn->real_escape_string($customer_name);
  $customer_email = $conn->real_escape_string($customer_email);
  $payment_method = $conn->real_escape_string($payment_method);

  // SQL Query to create the purchase
  $sql = "INSERT INTO purchases (cart_id, customer_name, customer_email, payment_method, purchase_date)
          VALUES (".$cart_id.", '".$customer_name."', '".$customer_email."', '".$payment_method."', NOW())";

  if ($conn->query($sql) === TRUE) {
    return TRUE; // Success
  } else {
    return FALSE; // Failure
  }
}

// --- Example Usage (Demonstration) ---

// 1. Assume we have a cart ID of 5 (This would typically come from a form submission)
$cart_id = 5;

// 2. Get customer information (This would typically be taken from a form)
$customer_name = $_POST['customer_name']; // Get from form input
$customer_email = $_POST['customer_email']; // Get from form input
$payment_method = $_POST['payment_method']; // Get from form input


// 3.  Create the purchase
$purchase_created = createPurchase($cart_id, $customer_name, $customer_email, $payment_method);

// 4. Display the result
if ($purchase_created) {
  echo "Purchase created successfully!  Cart ID: " . $cart_id;
} else {
  echo "Error creating purchase. Please try again.";
}


// ---  Database Table Structure (Example) ---
/*
CREATE TABLE purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    purchase_date DATETIME DEFAULT NOW()
);
*/


?>
