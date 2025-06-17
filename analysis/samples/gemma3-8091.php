    <br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name">
    <br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email">
    <br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
        <option value="other">Other</option>
    </select>
    <br><br>

    <input type="submit" value="Purchase">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_db";
$db_user = "your_user";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Important for security - add more validation as needed)
  $name = $_POST["name"];
  $quantity = $_POST["quantity"];
  $price = $_POST["price"];

  if (empty($name) || empty($quantity) || empty($price)) {
    $error_message = "Please fill in all fields.";
  } elseif (!is_numeric($quantity) || !is_numeric($price)) {
    $error_message = "Quantity and Price must be numeric values.";
  } else {
    // Sanitize input - crucial for preventing SQL injection
    $name = htmlspecialchars($name);
    $quantity = (int)$quantity; // Convert to integer
    $price = (float)$price;   // Convert to float

    // SQL query (Use prepared statements for better security)
    $sql = "INSERT INTO products (product_name, quantity, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Prepare the statement
    $stmt->bind_param("ssi", $name, $quantity, $price); // Bind parameters
    $stmt->execute();

    // Success message
    $success_message = "Product added successfully!";

    // Reset variables
    $name = "";
    $quantity = "";
    $price = "";
  }
}

// Database connection (Establish connection here)
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
