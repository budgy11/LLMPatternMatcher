

<?php

// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase processing
function processPurchase($cart, $customer_name, $customer_email, $shipping_address) {
    global $conn;

    // 1. Create a new order in the orders table
    $sql_order = "INSERT INTO orders (customer_name, customer_email, order_date, shipping_address)
                  VALUES ('" . mysqli_real_escape_string($conn, $customer_name) . "',
                         '" . mysqli_real_escape_string($conn, $customer_email) . "',
                         NOW(),
                         '" . mysqli_real_escape_string($conn, $shipping_address) . "')";

    if ($conn->query($sql_order) === false) {
        echo "Error creating order: " . $conn->error;
        return false;
    }

    $order_id = $conn->insert_id; // Get the ID of the newly created order

    // 2. Create order items (one row per product in the cart)
    foreach ($cart as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity)
                      VALUES (" . $conn->insert_id . ",
                             " . $conn->insert_id . ",
                             " . $conn->insert_id . ")";  // Corrected SQL query

        if ($conn->query($sql_item) === false) {
            echo "Error creating order item: " . $conn->error;
            // Optionally, rollback the order creation if an item insertion fails
            $conn->rollback();
            return false;
        }
    }

    // 3.  Commit the changes
    $conn->commit();

    return true; // Purchase successful
}


// --- Example Usage (Illustrative - replace with your form data handling) ---

// **IMPORTANT:**  This is a simplified example.  You will need to:
// 1.  Handle form submission with POST data.
// 2.  Validate and sanitize the input data *before* using it.
// 3.  Implement proper error handling and user feedback.


// Simulate form data (replace with actual form data)
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$shipping_address = $_POST['shipping_address'];

// Cart - Example Cart Data (replace with your cart logic)
$cart = [
    ['product_id' => 1, 'quantity' => 2],
    ['product_id' => 2, 'quantity' => 1],
];


// Process the purchase
if (processPurchase($cart, $customer_name, $customer_email, $shipping_address)) {
    echo "Purchase successful! Order ID: " . $conn->insert_id;
} else {
    echo "Purchase failed.";
}

// Close the database connection (always do this)
$conn->close();

?>
