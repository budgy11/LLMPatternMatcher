    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" required><br><br>

    <label for="shipping_address">Shipping Address:</label>
    <input type="text" id="shipping_address" name="shipping_address" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// Function to handle adding a purchase
function addPurchase($cartId, $userId, $orderDate) {
  // Validate inputs (Important for security)
  if (!$cartId || !$userId || !$orderDate) {
    return false; // Or throw an exception
  }

  // Prepare the SQL statement
  $sql = "INSERT INTO purchases (cart_id, user_id, order_date) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("iii", $cartId, $userId, $orderDate);

  // Execute the statement
  if ($stmt->execute()) {
    return true;
  } else {
    // Handle errors (log them, display to user, etc.)
    error_log("Error adding purchase: " . $conn->error);
    return false;
  }

  // Close the statement
  $stmt->close();
}



// --- Example Usage (Simulating a Purchase Request) ---

// 1. Get Cart ID and User ID (from a form, API, etc.)
$cartId = $_POST['cart_id']; // Assuming data is sent via POST
$userId = $_POST['user_id'];
$orderDate = date("Y-m-d H:i:s"); // Get current timestamp for order date


// 2. Add the Purchase
if (addPurchase($cartId, $userId, $orderDate)) {
  echo "Purchase successful!  Cart ID: " . $cartId;
} else {
  echo "Purchase failed.  Please try again later.";
}



// --- Database Table Structure (Example) ---

// CREATE TABLE purchases (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   cart_id INT NOT NULL,
//   user_id INT NOT NULL,
//   order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
//   FOREIGN KEY (cart_id) REFERENCES carts(id),
//   FOREIGN KEY (user_id) REFERENCES users(id)
// );


// ---  Important Considerations and Best Practices ---

// 1.  Security:
//   - **Input Validation:** Always validate and sanitize user input (e.g., using `filter_var()`) to prevent SQL injection attacks.  This is *critical*.
//   - **Prepared Statements:** Use prepared statements (as shown above) to prevent SQL injection.  *Never* directly concatenate user input into SQL queries.
//   - **Error Handling:** Implement robust error handling to catch and log errors.  Don't expose error messages directly to the user, as they can be exploited.
//   - **Authentication/Authorization:**  Implement proper user authentication and authorization to ensure only authorized users can add purchases.

// 2.  Data Types:  Adjust the data types (INT, DATETIME, etc.) in your database table based on the actual data you're storing.

// 3.  Error Logging:  Use `error_log()` to log errors to a file or a logging system.  This is invaluable for debugging and monitoring.

// 4.  User Interface (UI):  This code is just the backend logic.  You'll need to create a form or API endpoint to handle the purchase request from the user's browser or other application.

// 5.  Cart and User Tables:  This code assumes you have existing `carts` and `users` tables in your database.  You'll need to define those tables as well.  The example table structure above provides a basic structure.

// 6.  Transaction Management (Advanced): For more complex scenarios (e.g., if a purchase involves updating inventory), consider using database transactions to ensure atomicity (all changes succeed or none do).

?>
