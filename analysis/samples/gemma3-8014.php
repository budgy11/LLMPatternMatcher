

<?php

// Database configuration (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'your_username';
$db_pass = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize input (Important for security!)
  $product_name = $_POST["product_name"];
  $quantity = (int)$_POST["quantity"];
  $price = (float)$_POST["price"];  // Use float for monetary values
  $customer_name = $_POST["customer_name"];

  //  More robust validation and sanitization can be added here
  //  e.g., check if the name is not empty, quantity is positive, etc.
  //  Also, use prepared statements to prevent SQL injection.

  //  Basic validation -  This is a minimal example.  Expand as needed.
  if (empty($product_name) || $quantity <= 0 || $price <= 0) {
    $error_message = "Please fill in all fields and ensure quantity and price are positive.";
  } else {
    // Prepare the SQL query (using prepared statements - RECOMMENDED)
    $sql = "INSERT INTO purchases (product_name, quantity, price, customer_name) 
            VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);  // $conn is your database connection object

    // Bind the parameters
    $stmt->bind_param("sss", $product_name, $quantity, $price);  // "sss" indicates 3 string parameters

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Purchase added successfully!";
    } else {
      $error_message = "Error adding purchase: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    
    //Close connection
    $conn->close();
    
    
  }

}
?>
