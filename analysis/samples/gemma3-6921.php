    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <input type="submit" value="Add Purchase">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// --- Helper Functions ---
/**
 * Connects to the database.
 *
 * @return mysqli|null Connection object or null if connection fails.
 */
function connectToDatabase() {
  global $db_host, $db_name, $db_user, $db_password;

  try {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
      throw new Exception('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
  } catch (Exception $e) {
    echo "Error connecting to database: " . $e->getMessage() . "<br>";
    return null;
  }
}

/**
 * Adds a new order to the database.
 *
 * @param mysqli $conn Database connection object.
 * @param string $product_id
 * @param int $quantity
 * @param string $customer_name
 * @param string $customer_email
 * @return int|false Order ID on success, or false on failure.
 */
function addOrder(mysqli $conn, $product_id, $quantity, $customer_name, $customer_email) {
  $order_date = date('Y-m-d H:i:s'); // Get current date and time

  $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, order_date)
          VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email', '$order_date')";

  if ($conn->query($sql) === TRUE) {
    return $conn->insert_id;  // Return the ID of the newly inserted row
  } else {
    echo "Error inserting order: " . $conn->error . "<br>";
    return false;
  }
}


// --- Purchase Functionality ---

// 1.  Handle form submission (if applicable)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $customer_name = $_POST['customer_name'];
  $customer_email = $_POST['customer_email'];

  // --- Validation (IMPORTANT!) ---
  if (empty($product_id) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
    echo "Please fill in all fields.";
  } elseif (!is_numeric($quantity) || $quantity <= 0) {
    echo "Invalid quantity. Quantity must be a positive number.";
  } else {
    // 2. Connect to the database
    $conn = connectToDatabase();

    if ($conn) {
      // 3. Add the order
      $order_id = addOrder($conn, $product_id, $quantity, $customer_name, $customer_email);

      if ($order_id) {
        echo "Order placed successfully! Order ID: " . $order_id . "<br>";
      } else {
        echo "Order placement failed.";
      }

      // Close the database connection
      $conn->close();
    }
  }
}

?>
