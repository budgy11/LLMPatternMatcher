

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_user";
$db_pass = "your_password";

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Purchase Functionality ---

/**
 * Processes a purchase.
 *
 * @param array $cart  The cart data (product ID, quantity).
 * @param string $customer_name  Customer's name.
 * @param string $customer_email Customer's email.
 * @return int|string  Order ID on success, or error message on failure.
 */
function processPurchase(array $cart, string $customer_name, string $customer_email) {
    // Validate cart data
    if (empty($cart)) {
        return "Error: Cart is empty.";
    }

    // Prepare SQL query for inserting order information
    $sql = "INSERT INTO orders (customer_name, customer_email, order_date) 
            VALUES ('" . $customer_name . "', '" . $customer_email . "', NOW())";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id; // Get the newly created order ID

        // Prepare SQL query for inserting order items
        $sql_items = "INSERT INTO order_items (order_id, product_id, quantity) 
                       SELECT " . $order_id . ", product_id, quantity 
                       FROM " . $conn->dbname . ".cart 
                       WHERE product_id IN (" . implode(',', array_keys($cart)) . ")";

        if ($conn->query($sql_items) === TRUE) {
            // Clear the cart after successful purchase
            clearCart($conn, array_keys($cart));
            return $order_id;
        } else {
            // Rollback the order if item insertion fails
            $conn->rollback();
            return "Error: Failed to insert order items.  Rollback performed.";
        }
    } else {
        return "Error: Failed to insert order.  Rollback performed.";
    }
}

/**
 * Clears the cart after a purchase.
 *
 * @param mysqli $conn Database connection.
 * @param array $product_ids Array of product IDs to remove from the cart.
 */
function clearCart(mysqli $conn, array $product_ids) {
    $sql = "DELETE FROM cart WHERE product_id IN (" . implode(',', $product_ids) . ")";
    if ($conn->query($sql) === TRUE) {
        echo "Cart cleared successfully!";
    } else {
        echo "Error clearing cart.";
    }
}



// --- Example Usage (Simulated) ---

// Simulate a product database (replace with your real product data)
$products = [
    1 => ["name" => "Laptop", "price" => 1200],
    2 => ["name" => "Mouse", "price" => 25],
    3 => ["name" => "Keyboard", "price" => 75]
];

// Simulate user input
$customer_name = $_POST["customer_name"];
$customer_email = $_POST["customer_email"];
$cart = $_POST["cart"]; // Array of product IDs and quantities

// Process the purchase
$order_id = processPurchase($cart, $customer_name, $customer_email);

// Display the result
echo "<br>Order ID: " . $order_id . "<br>";
echo "<br>Order Details:<br>";

// Display order details (replace with actual database retrieval)
if ($order_id) {
    $sql_order = "SELECT * FROM orders WHERE id = " . $order_id;
    $result = $conn->query($sql_order);

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        echo "Customer Name: " . $order["customer_name"] . "<br>";
        echo "Customer Email: " . $order["customer_email"] . "<br>";
        echo "Order Date: " . $order["order_date"] . "<br>";
        
        $sql_items = "SELECT product_id, quantity FROM order_items WHERE order_id = " . $order_id;
        $item_result = $conn->query($item_result);
        
        if ($item_result->num_rows > 0) {
          while ($item = $item_result->fetch_assoc()) {
            $product_name = "Product " . $item["product_id"]; //Replace this with real product name retrieval
            $product_price = $products[$item["product_id"]]["price"];
            echo "  - " . $product_name . " (Quantity: " . $item["quantity"] . ") - Price: $" . $product_price . "<br>";
          }
        }
    } else {
        echo "Order not found.";
    }
}

?>
