
</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "ecommerce_db";
$db_user = "root";
$db_pass = "your_password";

// Session management (for tracking user's shopping cart)
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];

  // Validate input (basic example - add more robust validation)
  if (!is_numeric($quantity) || $quantity <= 0) {
    $error_message = "Invalid quantity. Please enter a positive number.";
  } else {
    // Add product to the cart
    if (!isset($_SESSION["cart"])) {
      $_SESSION["cart"] = [];
    }

    $_SESSION["cart"][] = [
      "product_id" => $product_id,
      "quantity" => $quantity
    ];

    // Display success message
    $success_message = "Product added to cart!";
  }
}
?>
