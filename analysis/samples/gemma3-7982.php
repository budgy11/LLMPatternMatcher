
</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_user";
$dbPass = "your_password";
$dbName = "your_database";

//  Create a connection to the database
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle the purchase process
function handlePurchase($cart, $user_id) {
    global $conn;

    // 1. Calculate the total amount
    $totalAmount = 0;
    foreach ($cart as $item_id => $quantity) {
        // Fetch product details from the database
        $product = $conn->query("SELECT price, quantity FROM products WHERE id = $item_id");

        if ($product->num_rows > 0) {
            $row = $product->fetch_assoc();
            $totalAmount += $row['price'] * $quantity;
        } else {
            // Handle product not found error (e.g., log an error, display a message)
            error_log("Product with ID $item_id not found.");
            continue; // Skip to the next item in the cart.
        }
    }

    // 2. Insert order details into the orders table
    $order_id = $conn->query("INSERT INTO orders (user_id, total_amount, order_date) VALUES ($user_id, $totalAmount, NOW())");

    if (!$order_id) {
        error_log("Error inserting order details: " . $conn->error);
        return false;
    }

    // Get the newly created order ID
    $order_id = $conn->query("SELECT id FROM orders WHERE id = LAST_INSERT_ID()");

    if (!$order_id) {
        error_log("Error getting order ID: " . $conn->error);
        return false;
    }
    $order_id = $order_id->fetch_assoc()['id'];


    // 3.  Insert order items into the order_items table
    foreach ($cart as $item_id => $quantity) {
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, $item_id, $quantity)");
    }

    // 4.  Clear the cart (Important for a real application)
    $_SESSION['cart'] = [];

    return $order_id; // Return the order ID for confirmation
}

// Example Usage:

// 1.  Simulate user data (Replace with actual user authentication)
session_start();
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Default user ID 1

// 2. Simulate the cart data.  This should come from a session or form input.
$cart = [
    1 => 2, // Product ID 1, quantity 2
    3 => 1,  // Product ID 3, quantity 1
];


// 3.  Handle the purchase
$orderId = handlePurchase($cart, $userId);

if ($orderId) {
    echo "<h2>Purchase successful!</h2>";
    echo "<p>Order ID: " . $orderId . "</p>";
    echo "<p>Thank you for your purchase!</p>";
} else {
    echo "<h2>Purchase failed.  Please try again.</h2>";
}

?>
