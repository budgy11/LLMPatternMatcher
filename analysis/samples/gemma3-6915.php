
    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// This is a simplified example; you'll need to adapt it to your specific database setup.

// ---  Database Configuration (Replace with your actual values) ---
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";
// ---  End Database Configuration ---


// Function to handle the purchase process
function handlePurchase($orderData) {
    // 1. Validate Input (VERY IMPORTANT - Prevent SQL Injection!)
    $product_id = isset($orderData['product_id']) ? (int)$orderData['product_id'] : 0; // Type casting for safety
    $quantity = isset($orderData['quantity']) ? (int)$orderData['quantity'] : 1; // Default quantity
    $customer_email = isset($orderData['customer_email']) ? trim($orderData['customer_email']) : '';


    if ($product_id <= 0 || $quantity <= 0 || empty($customer_email)) {
        return "Invalid order data. Please check your input.";
    }

    // 2. Check Product Availability (Example - Adapt to your product table)
    $product_query = "SELECT stock FROM products WHERE id = $product_id";
    $product_result = $db->query($product_query);

    if ($product_result->num_rows > 0) {
        $product_stock = $product_result->fetch_assoc()['stock'];
        if ($product_stock < $quantity) {
            return "Product is out of stock.";
        }
    } else {
        return "Product not found.";
    }



    // 3.  Create Order Record in Database
    $sql = "INSERT INTO orders (customer_email, product_id, quantity, order_date) VALUES ('$customer_email', $product_id, $quantity, NOW())";

    if ($db->query($sql)) {
        // 4. Update Product Stock
        $update_stock_sql = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
        if ($db->query($update_stock_sql)) {
            return "Order placed successfully! Order ID: " . $db->insert_id; // Get the last inserted order ID
        } else {
            // Rollback:  If updating the stock fails, delete the order
            $delete_order_sql = "DELETE FROM orders WHERE id = $db->insert_id";
            if ($db->query($delete_order_sql)) {
                return "Order placed, but stock update failed. Please try again.";
            } else {
                return "Order placed, but stock update failed. Database error.";
            }
        }
    } else {
        return "Error placing order. Database error.";
    }
}



// ---  Example Usage (From a form submission) ---

// Assume you received data from a form
$orderData = [
    'product_id' => 1,
    'quantity' => 2,
    'customer_email' => 'test@example.com'
];



$result = handlePurchase($orderData);
echo "<p>" . $result . "</p>";


?>
