
  <a href="checkout.php">Checkout</a>
</body>
</html>


<?php

// Database connection (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'shop_db';
$db_user = 'your_username';
$db_pass = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate input
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $quantity = intval($_POST["quantity"]);  //Ensure quantity is an integer
  $item_id = intval($_POST["item_id"]);   //Ensure item_id is an integer

  // Input validation - add more checks as needed
  if (empty($name) || empty($email) || $quantity <= 0 || $item_id <= 0) {
    $error = "Please fill in all fields and ensure quantity and item_id are positive numbers.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // Prepare the data for insertion
    $sql = "INSERT INTO orders (name, email, quantity, item_id) VALUES ('$name', '$email', '$quantity', '$item_id')";

    // Execute the query
    if (mysqli_query($GLOBALS['conn'], $sql)) {
      $success = "Order placed successfully! Order ID: " . mysqli_insert_id($GLOBALS['conn']);
    } else {
      $error = "Error placing order: " . mysqli_error($GLOBALS['conn']);
    }
  }
}
// Connect to the database
$GLOBALS['conn'] = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($GLOBALS['conn']->connect_error) {
  die("Connection failed: " . $GLOBALS['conn']->connect_error);
}

?>
