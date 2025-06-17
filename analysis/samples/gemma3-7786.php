  <?php } ?>

  <hr>

  <a href="index.php">Continue Shopping</a>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (basic example - expand for production)
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $quantity = intval($_POST["quantity"]); // Ensure quantity is an integer

  // Sanitize and escape data for database insertion
  $name = mysqli_real_escape_string($db_host, $name); // Important for security
  $email = mysqli_real_escape_string($db_host, $email);
  $quantity = mysqli_real_escape_string($db_host, $quantity);

  // SQL query to insert data into the database
  $sql = "INSERT INTO orders (name, email, quantity) VALUES ('$name', '$email', '$quantity')";

  // Execute the query
  $result = mysqli_query($db_host, $sql);

  // Check if the query was successful
  if ($result) {
    $message = "Order placed successfully! Thank you, $name.";
  } else {
    $message = "Error placing order. Please try again.";
    // Log the error for debugging (optional)
    error_log(mysqli_error($db_host));
  }
} else {
  $message = ""; // Reset message for the initial page load
}
?>
