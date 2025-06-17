  <?php endif; ?>

  <p><a href="checkout.php">Proceed to Checkout</a></p>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Establish database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get product_id, quantity and action from the URL
$product_id = $conn->real_escape_string($_GET['product_id']); // Escape to prevent SQL injection
$quantity = (int)$_GET['quantity'];
$action = $_GET['action'];

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Handle action - update or remove
if ($action == 'update') {
  if (updateQuantity($conn, $product_id, $quantity, $user_id)) {
    echo "Cart updated successfully.";
  } else {
    echo "Error updating cart.";
  }
} elseif ($action == 'remove') {
  removeFromCart($conn, $product_id, $user_id);
  echo "Product removed from cart.";
} else {
  die("Invalid action."); //Handle incorrect requests
}

// Redirect back to cart.php
header("Location: cart.php");
exit; //Important:  Stop further script execution
?>
