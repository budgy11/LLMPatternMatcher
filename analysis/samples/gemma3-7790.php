    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required>

    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" placeholder="Quantity" required>

    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database connection (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add a new purchase
function addPurchase($product_id, $quantity, $customer_name, $customer_email) {
  global $conn; // Access the database connection

  // Sanitize inputs to prevent SQL injection
  $product_id = $conn->real_escape_string($product_id);
  $quantity = $conn->real_escape_string($quantity);
  $customer_name = $conn->real_escape_string($customer_name);
  $customer_email = $conn->real_escape_string($customer_email);

  // SQL query to insert the purchase data
  $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email)
          VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Success
  } else {
    return false; // Failure
  }
}

// Function to retrieve purchase details (e.g., for display)
function getPurchaseDetails($purchase_id) {
    global $conn;
    $sql = "SELECT * FROM purchases WHERE id = $purchase_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $purchase = $result->fetch_assoc();
        return $purchase;
    } else {
        return null;
    }
}


// --- Example Usage (Simplified - You'll need a form for user input) ---

// 1.  Handle Form Submission (This part is just a placeholder)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];

    // 2.  Add the Purchase (Call the function)
    if (addPurchase($product_id, $quantity, $customer_name, $customer_email)) {
        echo "Purchase added successfully!";
    } else {
        echo "Error adding purchase.";
    }
}


// --- Database Table Structure (MySQL) ---
/*
CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL
);
*/

?>
