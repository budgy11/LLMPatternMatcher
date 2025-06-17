
</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'e_commerce';
$db_user = 'root';
$db_pass = 'password';

// Function to connect to the database
function connectDB() {
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
  }
  return $conn;
}


// Function to handle purchase creation
function createPurchase($userId, $cartItems) {
  $conn = connectDB();

  // Build the SQL query
  $sql = "INSERT INTO purchases (user_id, created_at) VALUES (
            " . $conn->real_escape_string($userId) . ",
            NOW()
        )";

  if ($conn->query($sql) === TRUE) {
    $purchaseId = $conn->insert_id; // Get the last inserted ID
    // Insert cart items into the purchases_items table
    foreach ($cartItems as $item) {
      $sql_item = "INSERT INTO purchases_items (purchase_id, product_id, quantity) VALUES (
          " . $conn->real_escape_string($purchaseId) . ",
          " . $conn->real_escape_string($item['product_id']) . ",
          " . $conn->real_escape_string($item['quantity']) . "
      )";
      if (!$conn->query($sql_item)) {
        error_log("Error inserting cart item: " . $conn->error);  // Log the error
        return false; // Indicate failure
      }
    }

    $conn->commit(); // Commit the transaction
    return true;
  } else {
    error_log("Error creating purchase: " . $conn->error); // Log the error
    return false;
  }
}


// Example Usage (Simulating a purchase request)
// Assuming $userId and $cartItems are passed to this function
// from a form submission or other request
// $userId = 123;
// $cartItems = [
//   ['product_id' => 1, 'quantity' => 2],
//   ['product_id' => 2, 'quantity' => 1]
// ];


//  Simulate form submission (for testing)
if (isset($_POST['user_id']) && isset($_POST['cart_items'])) {
    $userId = $_POST['user_id'];
    $cartItems = json_decode($_POST['cart_items'], true); // Decode JSON string
} else {
    // Handle the case where the form hasn't been submitted
    $userId = null;
    $cartItems = [];
}


if ($userId !== null && !empty($cartItems)) {
  if (createPurchase($userId, $cartItems)) {
    echo "Purchase created successfully! Purchase ID: " . $conn->insert_id;
  } else {
    echo "Purchase creation failed.  Check the server error log for details.";
  }
} else {
  echo "Invalid user ID or cart items.";
}


// Database Table Structure (example)

/*
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10, 2)
);

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE purchases_items (
    purchase_id INT,
    product_id INT,
    quantity INT,
    PRIMARY KEY (purchase_id, product_id),
    FOREIGN KEY (purchase_id) REFERENCES purchases(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
*/
?>
