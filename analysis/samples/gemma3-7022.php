

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_pass = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Basic validation - extend this for production)
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $quantity = intval($_POST["quantity"]); // Ensure quantity is an integer
  $product_name = htmlspecialchars($_POST["product_name"]);

  // Check if required fields are filled
  if (empty($name) || empty($email) || empty($quantity) || empty($product_name)) {
    $error = "Please fill in all fields.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO orders (name, email, quantity, product_name) VALUES ('$name', '$email', '$quantity', '$product_name')";

    // Execute the query
    if (mysqli_query($db_connect, $sql)) {
      $success = "Order placed successfully!";
    } else {
      $error = "Error placing order: " . mysqli_error($db_connect);
    }
  }
}

// Connect to the database
$db_connect = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check the connection
if (!$db_connect) {
  die("Connection failed: " . mysqli_connect_error());
}

?>
