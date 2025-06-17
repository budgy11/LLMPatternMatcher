    </select>
    <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shop_db";
$db_user = "your_user";
$db_password = "your_password";

// Start the session
session_start();

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle purchase
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
    $conn = connectDB();

    // Update the cart status to 'completed'
    $update_cart_query = "UPDATE carts SET status = 'completed', completed_at = NOW() WHERE id = $cart_id";
    if ($conn->query($update_cart_query) === FALSE) {
        echo "Error updating cart: " . $conn->error;
        return false;
    }

    // Insert order details into the orders table
    $order_id = $conn->insert_id; // Get the auto-incremented ID of the new order
    $order_query = "INSERT INTO orders (cart_id, customer_name, customer_email, payment_method, order_date, order_id)
                    VALUES ($cart_id, '$customer_name', '$customer_email', '$payment_method', NOW(), $order_id)";

    if ($conn->query($order_query) === FALSE) {
        echo "Error inserting order: " . $conn->error;
        return false;
    }

    // Loop through the cart items and create corresponding order items
    $select_cart_items_query = "SELECT product_id, quantity FROM cart_items WHERE cart_id = $cart_id";
    $result = $conn->query($select_cart_items_query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row["product_id"];
            $quantity = $row["quantity"];

            $insert_order_item_query = "INSERT INTO order_items (order_id, product_id, quantity)
                                       VALUES ($order_id, $product_id, $quantity)";

            if ($conn->query($insert_order_item_query) === FALSE) {
                echo "Error inserting order item: " . $conn->error;
                return false;
            }
        }
    }


    // Clear the cart items
    $delete_cart_items_query = "DELETE FROM cart_items WHERE cart_id = $cart_id";
    if ($conn->query($delete_cart_items_query) === FALSE) {
        echo "Error deleting cart items: " . $conn->error;
        return false;
    }

    echo "Purchase successful! Order ID: " . $order_id;
    return true;
}

// Handle the purchase request (e.g., from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if cart_id is provided in the form.  Crucial for security!
    if (isset($_POST['cart_id']) && is_numeric($_POST['cart_id'])) {
        $cart_id = $_POST['cart_id'];
        $customer_name = $_POST['customer_name'];
        $customer_email = $_POST['customer_email'];
        $payment_method = $_POST['payment_method'];

        // Call the handlePurchase function
        if (handlePurchase($cart_id, $customer_name, $customer_email, $payment_method)) {
            // Optionally, redirect the user to a confirmation page
            // header("Location: confirmation.php?order_id=$order_id");
            // exit();
        } else {
            echo "Purchase failed. Please try again.";
        }
    } else {
        echo "Invalid cart ID.";
    }
}
?>
