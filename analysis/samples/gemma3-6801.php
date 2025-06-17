

<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "shop";
$db_user = "your_username";
$db_password = "your_password";

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to add a purchase to the database
function addPurchase($product_id, $quantity, $customer_name, $customer_email) {
    global $conn;

    // Sanitize input (Important for security - prevent SQL injection)
    $product_id = $conn->real_escape_string($product_id);
    $quantity = $conn->real_escape_string($quantity);
    $customer_name = $conn->real_escape_string($customer_name);
    $customer_email = $conn->real_escape_string($customer_email);


    $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email, purchase_date) 
            VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email', NOW())";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Function to update product quantity
function updateProductQuantity($product_id, $quantity) {
    global $conn;

    // Sanitize input
    $product_id = $conn->real_escape_string($product_id);
    $quantity = $conn->real_escape_string($quantity);

    // Check if the product exists
    $check_query = "SELECT id FROM products WHERE id = '$product_id'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $sql = "UPDATE products SET stock_quantity = stock_quantity - '$quantity' WHERE id = '$product_id'";

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    } else {
        return false; // Product doesn't exist
    }
}

// --------------------  Example Usage (for demonstration) --------------------

// 1. Handle Purchase Request (e.g., from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];

    // 2. Add Purchase to Database
    if (addPurchase($product_id, $quantity, $customer_name, $customer_email)) {
        echo "<p>Purchase successful!  Product ID: " . $product_id . ", Quantity: " . $quantity . "</p>";

        // 3. Update Product Quantity
        if (updateProductQuantity($product_id, $quantity)) {
            echo "<p>Product quantity updated successfully.</p>";
        } else {
            echo "<p>Error updating product quantity.</p>";
        }
    } else {
        echo "<p>Error adding purchase to database.</p>";
    }
}

// --------------------  Database Setup (Example - create tables) --------------------
// You'll need to run this SQL script separately (e.g., using a database management tool)
/*
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0
);

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Example data
INSERT INTO products (product_name, description, price, stock_quantity) VALUES
('Laptop', 'High-performance laptop', 1200.00, 10),
('Mouse', 'Wireless mouse', 25.00, 50);
*/


?>
