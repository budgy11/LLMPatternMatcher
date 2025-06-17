    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database Connection (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase
function handlePurchase($cart, $conn) {
    // Calculate total price
    $totalPrice = 0;
    foreach ($cart as $item_id => $quantity) {
        //  Assuming you have a product table with 'id' and 'price' columns
        $product_query = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($product_query);
        $stmt->bind_param("i", $item_id); // "i" for integer
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $totalPrice += $row['price'] * $quantity;
        } else {
            // Handle product not found -  log an error, display an error message, etc.
            echo "Error: Product with ID " . $item_id . " not found.";
            return false; // Or handle differently, perhaps continue without this item
        }
        $stmt->close();
    }

    //  Simulate payment processing (Replace with your actual payment gateway integration)
    echo "<br>Total Price: $" . $totalPrice;
    echo "<br>Payment Successful!";
    // TODO: Integrate with your payment gateway here.

    // Insert order into the database
    $order_id = $conn->insert_id; // Get the last inserted ID (useful for unique order IDs)
    $order_date = date("Y-m-d H:i:s");

    $order_query = "INSERT INTO orders (user_id, order_date) VALUES (?, ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("is", null, $order_date); // Null for user_id - assuming you have a user table and associate based on that
    $stmt->execute();
    $stmt->close();



    // Insert order items into the orders_items table
    foreach ($cart as $item_id => $quantity) {
        $order_item_query = "INSERT INTO orders_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($order_item_query);
        $stmt->bind_param("iii", $order_id, $item_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    return true; // Indicate purchase was successful
}


// --- Example Usage ---

//  1. Get the cart data (This is just an example - fetch from session, database, etc.)
$cart = [
    1 => 2, // Product ID 1, quantity 2
    3 => 1, // Product ID 3, quantity 1
];

// 2. Handle the purchase
if (handlePurchase($cart, $conn)) {
    echo "<br>Your order has been placed successfully!";
    // Clear the cart (e.g., update session, delete from database)
    unset($cart);
} else {
    echo "<br>Purchase failed.";
}


// --- Database Setup (Create these tables if they don't exist) ---

// products table
/*
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);
*/

// users table (if you have user accounts)
/*
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL
);
*/

// orders table
/*
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_date DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
*/

// orders_items table
/*
CREATE TABLE orders_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
*/
?>
