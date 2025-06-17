

<?php

// Database connection (Replace with your actual database credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_pass = "your_password";
$db_name = "your_database";

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

    // 1.  Get Cart Items
    $sql = "SELECT product_id, quantity FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id); // i for integer
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }

    if (empty($cart_items)) {
        $conn->close();
        return false; // No items in the cart
    }

    // 2. Calculate Total Amount
    $total_amount = 0;
    foreach ($cart_items as $item) {
        // Assuming you have a products table with price
        // Replace 'product_id' and 'price' with your actual table/column names
        $sql_product = "SELECT price FROM products WHERE product_id = ?";
        $stmt_product = $conn->prepare($sql_product);
        $stmt_product->bind_param("i", $item['product_id']);
        $stmt_product->execute();
        $product_result = $stmt_product->get_result();

        if ($product_row = $product_row->fetch_assoc()) {
            $total_amount += $product_row['price'] * $item['quantity'];
        } else {
            echo "Error: Product with ID " . $item['product_id'] . " not found.<br>";
            $conn->close();
            return false;
        }
    }

    // 3. Insert Order into the Orders table
    $sql_order = "INSERT INTO orders (cart_id, customer_name, customer_email, total_amount, payment_method)
                  VALUES (?, ?, ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("isss", $cart_id, $customer_name, $customer_email, $total_amount);
    $stmt_order->execute();
    $order_id = $conn->insert_id;

    // 4.  Update Cart (Mark as Purchased)
    foreach ($cart_items as $item) {
        $sql_update_cart = "UPDATE cart SET purchased = 1 WHERE product_id = ? AND cart_id = ?";
        $stmt_update = $conn->prepare($sql_update_cart);
        $stmt_update->bind_param("is", $item['product_id'], $cart_id);
        $stmt_update->execute();
    }

    $conn->close();
    return $order_id; // Return the order ID
}


// --- Example Usage (For testing -  This is where you'd integrate with a form and display success messages) ---

// Sample Cart ID (This should come from the cart ID passed from a form)
$cart_id = 1;

// Sample Customer Information (This would be taken from a form)
$customer_name = "John Doe";
$customer_email = "john.doe@example.com";
$payment_method = "Credit Card";

// Handle the purchase
$order_id = handlePurchase($cart_id, $customer_name, $customer_email, $payment_method);

if ($order_id) {
    echo "Purchase successful! Order ID: " . $order_id;
} else {
    echo "Purchase failed.";
}

?>
