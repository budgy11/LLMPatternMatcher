    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email"><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" max="100" value="1"><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" min="0.01" value="10.00"><br><br>

    <button type="submit">Place Order</button>
  </form>

  <p>Note: This is a simplified example.  In a real application, you would handle errors, security, and potentially integrate with a payment gateway.</p>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Handles the purchase process.
 *
 * @param array $order_data  An associative array containing the order details.
 *                          Example:
 *                          [
 *                              'product_id' => 123,
 *                              'quantity' => 2,
 *                              'customer_name' => 'John Doe',
 *                              'customer_email' => 'john.doe@example.com'
 *                          ]
 */
function processPurchase(array $order_data) {
    // Validate Input (Crucial for security!)
    if (empty($order_data['product_id']) || empty($order_data['quantity']) || empty($order_data['customer_name']) || empty($order_data['customer_email'])) {
        return "Error: Missing required fields.";
    }
    
    $product_id = $order_data['product_id'];
    $quantity = $order_data['quantity'];
    $customer_name = $order_data['customer_name'];
    $customer_email = $order_data['customer_email'];

    // Validate Quantity (e.g., ensure it's a positive integer)
    if (!is_numeric($quantity) || $quantity <= 0) {
        return "Error: Invalid quantity.";
    }

    // *** In a real application, you'd implement proper input validation and sanitization here ***
    // This is a simplified example; a production system would have more robust validation.

    // --- Database Interaction ---

    // 1. Check if the product exists.
    $sql_check_product = "SELECT id FROM products WHERE id = ?";
    $stmt_check_product = $conn->prepare($sql_check_product);
    $stmt_check_product->bind_param("i", $product_id);
    $stmt_check_product->execute();
    $result = $stmt_check_product->get_result();

    if ($result->num_rows == 0) {
        $stmt_check_product->close();
        return "Error: Product not found.";
    }


    // 2.  Insert the order into the orders table
    $sql_insert_order = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, order_date) VALUES (?, ?, ?, ?, NOW())";
    $stmt_insert_order = $conn->prepare($sql_insert_order);
    $stmt_insert_order->bind_param("isss", $product_id, $quantity, $customer_name, $customer_email);
    $stmt_insert_order->execute();

    if ($stmt_insert_order->get_affected_rows() === 0) {
        $stmt_insert_order->close();
        return "Error: Failed to insert order into the database.";
    }

    $stmt_insert_order->close();

    // 3.  Update the product's stock (example)
    $sql_update_stock = "UPDATE products SET stock = stock - ? WHERE id = ?";
    $stmt_update_stock = $conn->prepare($sql_update_stock);
    $stmt_update_stock->bind_param("is", $quantity, $product_id);
    $stmt_update_stock->execute();

    if ($stmt_update_stock->get_affected_rows() === 0) {
        // Handle product stock update failure (e.g., log the error)
        // Consider what to do if stock isn't updated, like rollback the order.
        return "Error: Failed to update product stock.";
    }

    $stmt_update_stock->close();

    return "Order placed successfully! Order ID: " . $conn->insert_id; //Returns the auto-generated Order ID
}


// --- Example Usage (Simulated Form Handling) ---

//  Simulate receiving the order data from a form
$order_data = [
    'product_id' => 123,
    'quantity' => 2,
    'customer_name' => 'Jane Smith',
    'customer_email' => 'jane.smith@example.com'
];

// Process the purchase
$result = processPurchase($order_data);

// Display the result
echo "<pre>";
echo $result;
echo "</pre>";



// --- Database Setup (Example Tables) ---

//  Important:  Create these tables in your database!
/*
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    stock INT NOT NULL DEFAULT 0
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/

?>
