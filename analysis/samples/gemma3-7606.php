        <br><br>

        <button type="submit">Place Order</button>
    </form>

    <br><br>
    <a href="index.php">Return to Home</a>

</body>
</html>


<?php

// Database connection details (Replace with your actual values)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (Important for security)
  $name = $_POST["name"];
  $quantity = $_POST["quantity"];
  $price = $_POST["price"]; // Assuming price is already in a numeric format

  if (empty($name) || empty($quantity) || empty($price)) {
    $error = "Please fill in all fields.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    $error = "Quantity must be a positive number.";
  } elseif (!is_numeric($price) || $price <= 0) {
    $error = "Price must be a positive number.";
  } else {
    // Process the purchase
    // 1. Insert data into the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO purchases (name, quantity, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Use prepared statement for security
    $stmt->bind_param("sii", $name, $quantity, $price); // s = string, i = integer
    if ($stmt->execute()) {
      $success = "Purchase successful! Order ID: " . $conn->insert_id;
    } else {
      $error = "Purchase failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
  }
}
?>
