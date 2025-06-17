

<?php

// Function to handle the purchase process
function handlePurchase() {
  // Get user input
  $productName = $_POST['productName'];
  $quantity = (int)$_POST['quantity']; // Cast to integer
  $price = (float)$_POST['price'];  // Cast to float (for decimals)

  // Validate input (very important!)
  if (empty($productName) || $quantity <= 0 || $price <= 0) {
    echo "<p>Invalid input. Please enter a product name, a positive quantity, and a positive price.</p>";
    return;
  }


  // Calculate the total cost
  $totalCost = $quantity * $price;

  // Display the purchase details
  echo "<p><strong>Product:</strong> " . htmlspecialchars($productName) . "</p>";
  echo "<p><strong>Quantity:</strong> " . $quantity . "</p>";
  echo "<p><strong>Price per item:</strong> $" . $price . "</p>";
  echo "<p><strong>Total Cost:</strong> $" . number_format($totalCost, 2) . "</p>"; // Format with 2 decimal places
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  handlePurchase();
}
?>
