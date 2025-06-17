

<?php

// Database connection (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_user";
$db_password = "your_password";

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase
function handlePurchase($cart, $user_id) {
    // 1. Validate Cart and User

    // Check if the cart is empty
    if (empty($cart)) {
        return "Cart is empty.  Cannot complete purchase.";
    }

    // In a real application, you'd authenticate the user and verify they have an active account.
    // This is a placeholder for demonstration purposes.  Replace with your authentication logic.
    // In this example, we assume the user exists.
    
    // 2. Create Order Record in Database
    $order_id = $conn->insert("orders",
        array(
            "user_id" => $user_id,
            "order_date" => date("Y-m-d H:i:s")
        )
    );

    if ($order_id === false) {
        return "Error creating order in database.";
    }

    $order_id = $conn->insert_id;

    // 3. Process Order Items
    foreach ($cart as $item_id => $quantity) {
        // Get product details
        $product = $conn->select_one("products", "product_id = " . $item_id);

        if (!$product) {
            return "Product with ID " . $item_id . " not found.";
        }

        // Update inventory (Subtract quantity)
        $conn->query("UPDATE products SET stock = stock - " . $quantity . " WHERE product_id = " . $item_id);

        // Add order item record
        $conn->insert("order_items",
            array(
                "order_id" => $order_id,
                "product_id" => $item_id,
                "quantity" => $quantity,
                "item_price" => $product["price"] // Assuming you have a 'price' column in your products table
            )
        );
    }

    return "Purchase completed successfully! Order ID: " . $order_id;
}

// --- Example Usage ---

// 1. Assume a user ID (Replace with actual user ID)
$userId = 1;

// 2. Assume a cart (This is a placeholder - retrieve from session or database)
$cart = array(
    1 => 2, // Product ID 1, quantity 2
    2 => 1  // Product ID 2, quantity 1
);


// 3. Handle the purchase
$result = handlePurchase($cart, $userId);

// 4. Display the result
echo "<pre>" . print_r($result, true) . "</pre>";

// ---  Database Setup (Example - CREATE TABLE statements) ---

// This is for setting up the database.  Execute these statements in your MySQL database.

// CREATE TABLE users (
//     user_id INT AUTO_INCREMENT PRIMARY KEY,
//     username VARCHAR(255) NOT NULL,
//     password VARCHAR(255) NOT NULL,
//     email VARCHAR(255) NOT NULL
// );

// CREATE TABLE products (
//     product_id INT AUTO_INCREMENT PRIMARY KEY,
//     product_name VARCHAR(255) NOT NULL,
//     description TEXT,
//     price DECIMAL(10, 2) NOT NULL,
//     stock INT NOT NULL DEFAULT 0
// );

// CREATE TABLE orders (
//     order_id INT AUTO_INCREMENT PRIMARY KEY,
//     user_id INT NOT NULL,
//     order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (user_id) REFERENCES users(user_id)
// );

// CREATE TABLE order_items (
//     item_id INT AUTO_INCREMENT PRIMARY KEY,
//     order_id INT NOT NULL,
//     product_id INT NOT NULL,
//     quantity INT NOT NULL,
//     item_price DECIMAL(10, 2) NOT NULL,
//     FOREIGN KEY (order_id) REFERENCES orders(order_id),
//     FOREIGN KEY (product_id) REFERENCES products(product_id)
// );

// Example Insertion (Insert some sample data)
// INSERT INTO users (username, password, email) VALUES ('user1', 'password123', 'user1@example.com');
// INSERT INTO products (product_name, description, price, stock) VALUES ('T-Shirt', 'Cotton T-Shirt', 19.99, 100);
// INSERT INTO products (product_name, description, price, stock) VALUES ('Jeans', 'Denim Jeans', 49.99, 50);


?>
