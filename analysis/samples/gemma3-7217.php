

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce_db";
$db_user = "your_username";
$db_password = "your_password";

// **1. Check if the form has been submitted**
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // **2. Validate the form data (Important for security!)**
  $name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
  $email = isset($_POST["email"]) ? filter_var($_POST["email"], FILTER_SANITIZE_EMAIL) : "";
  $address = isset($_POST["address"]) ? trim($_POST["address"]) : "";
  $product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : 0; // Ensure product_id is an integer

  // Basic validation - Add more robust validation as needed
  if (empty($name) || empty($email) || empty($address) || $product_id <= 0) {
    $errors = "Please fill in all fields correctly.";
  } else {
    // **3. Prepare the data for insertion into the database**

    // Escape data to prevent SQL injection
    $name = mysqli_real_escape_string($db_connection, $name);
    $email = mysqli_real_escape_string($db_connection, $email);
    $address = mysqli_real_escape_string($db_connection, $address);
    $product_id = intval($product_id);  //Make sure it's an integer

    // **4. Insert the data into the database**

    // Establish database connection
    $db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

    if (mysqli_connect_errno()) {
      die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO orders (name, email, address, product_id) VALUES ('$name', '$email', '$address', $product_id)";

    if (mysqli_query($db_connection, $sql)) {
      $success_message = "Order placed successfully! Order ID: " . mysqli_insert_id($db_connection);
    } else {
      $error_message = "Error placing order: " . mysqli_error($db_connection);
    }
  }
}
?>
