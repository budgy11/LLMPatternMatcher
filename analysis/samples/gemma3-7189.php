
</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecom_db";
$db_user = "your_username";
$db_pass = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
    $conn = connectToDatabase();

    // 1. Get Cart Details
    $query = "SELECT product_id, quantity FROM cart WHERE cart_id = '$cart_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $cart_items = array();
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }

        // 2. Calculate Total Amount
        $total_amount = 0;
        foreach ($cart_items as $item) {
            // Assuming you have a products table with 'product_id' and 'price'
            $query_product = "SELECT price FROM products WHERE product_id = '$item['product_id']'";
            $result_product = $conn->query($query_product);

            if ($result_product->num_rows > 0) {
                $price = $result_product->fetch_assoc()['price'];
                $total_amount += $price * $item['quantity'];
            } else {
                // Handle the case where the product is not found
                echo "Error: Product with ID '$item['product_id']' not found.";
                return false; // Or handle this differently (e.g., skip the item)
            }
        }


        // 3. Update Order Table
        $order_id = $conn->insert("orders", [
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'total_amount' => $total_amount,
            'order_date' => date('Y-m-d H:i:s') // Get current timestamp
        ]);

        // 4. Update Cart (Mark items as sold)
        foreach ($cart_items as $item) {
            $conn->query("UPDATE cart SET quantity = 0 WHERE product_id = '$item['product_id']' AND cart_id = '$cart_id'");
        }

        // 5. Return Success Message
        return $conn->insert_id; // Return the newly created order ID
    } else {
        // Cart is empty or doesn't exist
        return false;
    }
}

// --- Example Usage (Simulated Form Handling) ---

// 1. Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data (replace with your actual form processing)
    $cart_id = $_POST["cart_id"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $payment_method = $_POST["payment_method"];

    // Validate the data (important for security)
    if (empty($cart_id) || empty($customer_name) || empty($customer_email)) {
        echo "Error: Please fill in all required fields.";
    } else {
        // Handle the purchase
        $new_order_id = handlePurchase($cart_id, $customer_name, $customer_email, $payment_method);

        if ($new_order_id) {
            echo "Order placed successfully! Order ID: " . $new_order_id;
        } else {
            echo "Error placing order.";
        }
    }
}

?>
