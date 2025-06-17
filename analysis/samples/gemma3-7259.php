    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="product">Product:</label>
    <input type="text" id="product" name="product" required><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add a new order
function addOrder($productId, $quantity, $customerName, $customerEmail) {
    global $conn;

    // Sanitize and validate inputs (IMPORTANT!)
    $productId = mysqli_real_escape_string($conn, $productId);
    $quantity = mysqli_real_escape_string($conn, intval($quantity)); // Ensure quantity is an integer
    $customerName = mysqli_real_escape_string($conn, $customerName);
    $customerEmail = mysqli_real_escape_string($conn, $customerEmail);


    // Check if the product exists
    $product_query = "SELECT id, name, price FROM products WHERE id = '$productId'";
    $product_result = $conn->query($product_query);

    if (!$product_result->num_rows) {
        return false; // Product not found
    }

    $product = $product_result->fetch_assoc();
    $product_price = $product['price'];
    $total_price = $product_price * $quantity;

    // Insert the order into the orders table
    $order_query = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, total_price)
                    VALUES ('$productId', '$quantity', '$customerName', '$customerEmail', '$total_price')";

    if ($conn->query($order_query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// --- Example Usage (Handle form submission) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $customerName = $_POST["customer_name"];
    $customerEmail = $_POST["customer_email"];

    // Add the order
    if (addOrder($productId, $quantity, $customerName, $customerEmail)) {
        echo "Order placed successfully!  Order ID: " . $conn->insert_id; // Display the newly created order ID.
    } else {
        echo "Failed to place order.  Please try again.";
    }
}

// --- Database Table Setup (CREATE TABLE statements) ---

//  This code is included to demonstrate how the database tables would be structured.
//  You need to execute these queries to set up the database.

/*
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
*/


?>
