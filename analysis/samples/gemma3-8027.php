    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <input type="submit" value="Add to Cart">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$dbHost = "localhost";
$dbName = "shopping_cart";
$dbUser = "your_user";
$dbPass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $productName = $_POST["product_name"];
  $quantity = $_POST["quantity"];
  $price = $_POST["price"]; // Assuming price is already in the form

  // Input validation - crucial for security and data integrity
  if (empty($productName) || empty($quantity) || empty($price)) {
    $error = "Please fill in all fields.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error = "Quantity must be a positive number.";
  } elseif (!is_numeric($price) || $price <= 0) {
    $error = "Price must be a positive number.";
  } else {
    // Perform the purchase
    $total = $quantity * $price;

    // Log the purchase (optional, but highly recommended)
    $logMessage = "Product: " . $productName . ", Quantity: " . $quantity . ", Price: " . $price . ", Total: " . $total . ", Timestamp: " . date("Y-m-d H:i:s") . "
";
    file_put_contents("purchase_log.txt", $logMessage, FILE_APPEND);  // Append to the log file

    // Display success message
    echo "<p style='color: green;'>Purchase successful!  Total: $" . $total . "</p>";

    // You could also redirect to a confirmation page here.
    // echo "<p>You can view your order details here: <a href='confirmation.php'>Confirmation</a></p>";
  }
}
?>
