

<?php

// Database connection details (Replace with your actual values)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate input
  $product_name = trim($_POST["product_name"]);
  $quantity = intval($_POST["quantity"]);
  $price = floatval($_POST["price"]);

  // Basic validation
  if (empty($product_name) || $quantity <= 0 || $price <= 0) {
    $error_message = "Please fill in all fields and ensure quantities and prices are positive.";
  } else {
    // Add the item to the cart (This is a simplified example - you'll likely want a database)
    $cart_item = array(
      "product_name" => $product_name,
      "quantity" => $quantity,
      "price" => $price
    );

    // *** IMPORTANT: In a real application, you would store this data in a database ***
    // For demonstration, we'll store it in an array
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
    }
    $_SESSION['cart'][] = $cart_item;

    // Display a success message
    $success_message = "Item added to cart!";
  }
}

// Start the session
session_start();

// Display the cart contents (Simplified)
if (isset($_SESSION['cart'])) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";
  echo "<p><a href='checkout.php'>Checkout</a></p>"; // Link to checkout page
} else {
  echo "<p>Your cart is empty.</p>";
}

?>
