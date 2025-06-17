      <label for="product_id">Product ID:</label>
      <input type="number" id="product_id" name="product_id" required><br><br>

      <label for="quantity">Quantity:</label>
      <input type="number" id="quantity" name="quantity" required><br><br>

      <label for="customer_name">Customer Name:</label>
      <input type="text" id="customer_name" name="customer_name" required><br><br>

      <label for="customer_email">Customer Email:</label>
      <input type="email" id="customer_email" name="customer_email" required><br><br>

      <input type="submit" value="Place Order">
    </form>
  </div>
</body>
</html>


<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Connection to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add a new purchase
function addPurchase($productId, $quantity, $userId) {
  global $conn;

  // Validate input (important for security)
  if (!$productId || !$quantity || !$userId) {
    return false; // Or throw an exception
  }

  // Sanitize inputs to prevent SQL injection
  $productId = mysqli_real_escape_string($conn, $productId);
  $quantity = (int)$quantity; // Convert quantity to integer
  $userId = mysqli_real_escape_string($conn, $userId);


  // Check if the product exists
  $check_query = "SELECT id FROM products WHERE id = '$productId'";
  $result = $conn->query($check_query);

  if ($result->num_rows > 0) {
    // Product exists, proceed with adding the purchase
    $sql = "INSERT INTO purchases (product_id, quantity, user_id) VALUES ('$productId', '$quantity', '$userId')";

    if ($conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  } else {
    return false; // Product does not exist
  }
}

// Function to handle the purchase request (example)
function handlePurchaseRequest() {
  // Get form data (e.g., from a POST request)
  $productId = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $userId = $_POST['user_id']; // Get user ID from the session or wherever you store it

  // Validate input (more robust validation is recommended)
  if (!is_numeric($quantity) || $quantity <= 0) {
      echo "Invalid quantity.";
      return;
  }

  // Add the purchase
  if (addPurchase($productId, $quantity, $userId)) {
      echo "Purchase successful! Order ID: " . $conn->insert_id;  // Get the inserted ID
  } else {
      echo "Purchase failed.";
  }
}

// Example usage (if this is a web page)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  handlePurchaseRequest();
}
?>
