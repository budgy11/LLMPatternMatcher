    <label for="userId">User ID:</label>
    <input type="number" id="userId" name="userId" required><br><br>

    <label for="productId">Product ID:</label>
    <input type="number" id="productId" name="productId" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Add to Purchase</button>
  </form>

</body>
</html>


<?php

// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate input (VERY IMPORTANT -  Expand this with more validation)
  $product_name = trim($_POST["product_name"]);
  $quantity = intval($_POST["quantity"]);
  $customer_name = trim($_POST["customer_name"]);
  $customer_email = trim($_POST["customer_email"]);

  // Basic input validation - Add more robust validation here
  if (empty($product_name) || $quantity <= 0 || empty($customer_name) || empty($customer_email)) {
    $error = "All fields are required.";
  } else {
    // Prepare SQL query - IMPORTANT for preventing SQL injection
    $sql = "INSERT INTO orders (product_name, quantity, customer_name, customer_email)
            VALUES (?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sss", $product_name, $quantity, $customer_name, $customer_email);

    // Execute query
    if ($stmt->execute()) {
      $success = "Order placed successfully!";
    } else {
      $error = "Error placing order: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
  }
}

// Display form
?>
