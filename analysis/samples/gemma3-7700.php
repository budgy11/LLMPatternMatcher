    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" min="1" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// --- Database Connection (Replace with your actual database details) ---
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";
$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// --- Helper Functions ---
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// --- Purchase Functionality ---

function purchaseProduct($product_id, $quantity, $customer_name, $customer_email) {
  // 1. Input Validation & Sanitization
  $product_id = (int)$product_id; // Cast to integer
  $quantity = (int)$quantity;    // Cast to integer
  $customer_name = sanitizeInput($customer_name);
  $customer_email = sanitizeInput($customer_email);

  if ($quantity <= 0) {
    return "Invalid quantity. Please enter a positive number.";
  }

  // 2. Retrieve Product Information
  $sql = "SELECT id, name, price FROM products WHERE id = ?";
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("i", $product_id);  // 'i' for integer
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return "Product not found.";
  }
  $product = $result->fetch_assoc();

  // 3. Calculate Total Price
  $total_price = $product['price'] * $quantity;

  // 4. Update Inventory (Assuming you have an 'inventory' table)
  $sql = "UPDATE inventory SET quantity = quantity - ? WHERE product_id = ?";
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("is", $quantity, $product_id);
  $stmt->execute();

  // 5. Insert Order (Assuming you have an 'orders' table)
  $sql = "INSERT INTO orders (customer_name, customer_email, product_id, quantity, total_price, order_date)
          VALUES (?, ?, ?, ?, ?, CURDATE())";
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("sisd", $customer_name, $product_id, $quantity, $total_price);
  $stmt->execute();
  $order_id = $connection->insert_id;

  // 6.  Return Confirmation Message
  return "Order placed successfully! Order ID: " . $order_id;
}


// --- Example Usage (Demonstration) ---
// Simulate a form submission (e.g., from a HTML form)
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];


// Call the purchase function
$confirmation_message = purchaseProduct($product_id, $quantity, $customer_name, $customer_email);
echo $confirmation_message;

// --- Database Table Structures (Example) ---

// products table:
// id (INT, PRIMARY KEY)
// name (VARCHAR)
// price (DECIMAL)

// inventory table:
// id (INT, PRIMARY KEY)
// product_id (INT, FOREIGN KEY referencing products.id)
// quantity (INT)

// orders table:
// id (INT, PRIMARY KEY, AUTO_INCREMENT)
// customer_name (VARCHAR)
// customer_email (VARCHAR)
// product_id (INT, FOREIGN KEY referencing products.id)
// quantity (INT)
// total_price (DECIMAL)
// order_date (DATE)


?>
