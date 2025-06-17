        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <button type="submit">Purchase</button>
    </form>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize inputs
  $product_id = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
  $customer_name = filter_input(INPUT_POST, "customer_name", FILTER_SANITIZE_STRING);
  $customer_email = filter_input(INPUT_POST, "customer_email", FILTER_SANITIZE_EMAIL);

  // Error checking
  if (!$product_id || !$quantity || !$customer_name || !$customer_email) {
    $errors = ["product_id" => "Product ID is required.",
              "quantity" => "Quantity is required.",
              "customer_name" => "Customer Name is required.",
              "customer_email" => "Customer Email is required."];
  } elseif (!$product_id || !$quantity) {
    $errors = []; // Clear any previous errors
  } elseif (!$customer_name || !$customer_email) {
    $errors = []; // Clear any previous errors
  }

  // If no errors, proceed with the purchase
  if (!empty($errors)) {
    $_SESSION["errors"] = $errors; // Store errors in session for display
    // Redirect back to the product page with errors
    header("Location: product.php?product_id=$product_id");
    exit();
  }

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email)
          VALUES ($product_id, $quantity, '$customer_name', '$customer_email')";

  // Execute the query
  if ($conn->query($sql) === TRUE) {
    // Purchase successful
    echo "<h2>Purchase Successful!</h2>";
    echo "<p>Thank you for your order, " . $customer_name . "!</p>";

    // You can also:
    // - Redirect to a confirmation page
    // - Send an email confirmation
  } else {
    // Purchase failed
    echo "<h2>Purchase Failed!</h2>";
    echo "<p>" . $conn->error . "</p>";
  }

  $conn->close();
}
?>
