

<?php
// This file handles the order processing.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve data from the form
  $item = htmlspecialchars($_POST["item"]);
  $quantity = intval($_POST["quantity"]);

  // Basic validation (you should have more robust validation in a real application)
  if (empty($item) || $quantity <= 0) {
    echo "<p style='color: red;'>Invalid order. Please enter a valid item name and positive quantity.</p>";
  } else {
    //  Simulate order processing (e.g., save to a database)
    echo "<p style='color: green;'>Order placed successfully!  Item: " . $item . ", Quantity: " . $quantity . "</p>";
    // In a real application, you would:
    // 1. Connect to a database.
    // 2. Insert the order information into a table.
    // 3.  Generate an order confirmation number.
    // 4.  Send an email confirmation (optional).
  }
} else {
  // If the form was not submitted via POST.
  echo "<p>No order submitted.</p>";
}
?>
