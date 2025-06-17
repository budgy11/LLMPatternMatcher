        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" required>

        <label for="total_price">Total Price:</label>
        <input type="number" id="total_price" name="total_price" step="0.01" required>

        <button type="submit">Purchase</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// For demonstration purposes, let's assume you have a $db object
// and a function to connect to the database.

// Function to connect to the database
function connectToDatabase() {
    // Replace with your database credentials
    $host = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database_name";

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_amount) {
    $conn = connectToDatabase();

    // --- Validate Input (Important!) ---
    if (empty($cart_id) || empty($customer_name) || empty($customer_email) || $payment_amount <= 0) {
        return "Invalid input. Please check your order details.";
    }


    // --- Insert into the Orders Table ---
    $sql_insert_order = "INSERT INTO orders (cart_id, customer_name, customer_email, order_date, payment_amount)
                          VALUES ('$cart_id', '$customer_name', '$customer_email', NOW(), '$payment_amount')";

    if ($conn->query($sql_insert_order) === TRUE) {
        $order_id = $conn->insert_id; // Get the ID of the newly inserted order

        // --- Insert Order Items into the OrderItems Table ---
        $sql_insert_order_items = "INSERT INTO order_items (order_id, product_id, quantity)
                                   SELECT $order_id, product_id, quantity
                                   FROM cart_items
                                   WHERE product_id IN (SELECT product_id FROM cart_items WHERE cart_id = '$cart_id')";

        if ($conn->query($sql_insert_order_items) === TRUE) {
            // --- Clear the Cart ---
            $sql_delete_cart_items = "DELETE FROM cart_items WHERE cart_id = '$cart_id'";
            if ($conn->query($sql_delete_cart_items) === TRUE) {
                return "Purchase successful! Order ID: " . $order_id;
            } else {
                return "Error deleting cart items.  Order may be incomplete.";
            }
        } else {
            return "Error inserting order items. Order may be incomplete.";
        }
    } else {
        return "Error inserting order. Please try again.";
    }
}


// --- Example Usage (Simulated Form Handling) ---

// Assuming you have a form that collects the necessary data
// (e.g., cart_id, customer_name, customer_email, payment_amount)
// Let's assume these are passed from a form.

// Example form data (replace with actual form input)
$cart_id = $_POST['cart_id'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$payment_amount = $_POST['payment_amount'];

// Handle the purchase
$result = handlePurchase($cart_id, $customer_name, $customer_email, $payment_amount);

// Display the result
echo "<p>" . $result . "</p>";


// --- Database Table Structure (Example) ---

/*
CREATE TABLE carts (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE carts_items (
    cart_id INT PRIMARY KEY,
    product_id INT,
    quantity INT,
    FOREIGN KEY (cart_id) REFERENCES carts(cart_id)
);

CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255),
    price DECIMAL(10, 2)
);


CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    cart_id INT,
    customer_name VARCHAR(255),
    customer_email VARCHAR(255),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_amount DECIMAL(10, 2),
    FOREIGN KEY (cart_id) REFERENCES carts(cart_id)
);

CREATE TABLE order_items (
    order_id INT,
    product_id INT,
    quantity INT,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
*/


?>
