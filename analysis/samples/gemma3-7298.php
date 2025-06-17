    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required>

    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" value="1">

    <br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" min="0.01" step="0.01" value="10.00">  <!-- Adjust default value -->

    <br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
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

// --- Purchase Functionality ---

// Function to add a new purchase
function addPurchase($product_id, $quantity, $customer_name, $customer_email) {
  // Validate inputs (Crucial for security!)
  if (empty($product_id) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
    return false; // Indicate failure
  }

  // Sanitize inputs to prevent SQL injection
  $product_id = $conn->real_escape_string($product_id);
  $quantity = $conn->real_escape_string($quantity);
  $customer_name = $conn->real_escape_string($customer_name);
  $customer_email = $conn->real_escape_string($customer_email);

  // --- Query to Add Purchase ---
  $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email, purchase_date)
          VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email', NOW())";

  if ($conn->query($sql) === TRUE) {
    return true; // Indicate success
  } else {
    error_log("Query failed: " . $sql . " " . $conn->error); // Log the error for debugging
    return false;
  }
}


// --- Example Usage (This is just for demonstration) ---

// 1. Add a Purchase
$product_id = "123"; // Replace with a valid product ID
$quantity = "2";
$customer_name = "John Doe";
$customer_email = "john.doe@example.com";

if (addPurchase($product_id, $quantity, $customer_name, $customer_email)) {
  echo "Purchase added successfully!";
} else {
  echo "Failed to add purchase.";
}


// --- Sample Database Table Structure ---
// You need to create this table in your database:

/*
CREATE TABLE purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/

?>
