

<?php

// Database connection details (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database";

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add a new purchase
function addPurchase($userId, $productId, $quantity, $purchaseDate) {
  // Sanitize inputs (IMPORTANT - Prevent SQL Injection!)
  $userId = $conn->real_escape_string($userId);
  $productId = $conn->real_escape_string($productId);
  $quantity = $conn->real_escape_string($quantity);
  $purchaseDate = $conn->real_escape_string($purchaseDate);

  // SQL query to insert the purchase
  $sql = "INSERT INTO purchases (userId, productId, quantity, purchaseDate)
          VALUES ('$userId', '$productId', '$quantity', '$purchaseDate')";

  if ($conn->query($sql) === TRUE) {
    return true; // Success
  } else {
    return false; // Failure
  }
}


// --------------------  Handling the Purchase Functionality  --------------------

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate inputs (Implement more robust validation here)
  $userId = $_POST["userId"];
  $productId = $_POST["productId"];
  $quantity = $_POST["quantity"];

  // Check if the product ID exists (Basic validation)
  $sql_check_product = "SELECT id FROM products WHERE id = '$productId'";
  $result = $conn->query($sql_check_product);

  if ($result->num_rows > 0) {
    // Product exists, proceed with purchase
    $purchaseSuccessful = addPurchase($userId, $productId, $quantity, date("Y-m-d H:i:s")); // Use the current timestamp

    if ($purchaseSuccessful) {
      echo "<p style='color:green;'>Purchase added successfully!</p>";
    } else {
      echo "<p style='color:red;'>Failed to add purchase.  Please try again.</p>";
    }
  } else {
    echo "<p style='color:red;'>Product ID '$productId' does not exist.</p>";
  }
}
?>
