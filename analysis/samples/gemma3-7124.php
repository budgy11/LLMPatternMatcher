

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function processPurchase($orderData) {
    $conn = connectToDatabase();

    // Prepare the SQL query
    $sql = "INSERT INTO orders (product_id, quantity, user_id, order_date) 
            VALUES ('" . $conn->real_escape_string($orderData['product_id']) . "', 
                   '" . $conn->real_escape_string($orderData['quantity']) . "', 
                   '" . $conn->real_escape_string($orderData['user_id']) . "', 
                   NOW())";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        return true; // Purchase successful
    } else {
        return false; // Purchase failed
    }
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (Basic - add more robust validation as needed)
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_POST['user_id'];

    // Check if product_id and quantity are numeric
    if (is_numeric($product_id) && is_numeric($quantity)) {
        // Process the purchase
        $purchaseSuccessful = processPurchase(['product_id' => $product_id, 'quantity' => $quantity, 'user_id' => $user_id]);

        if ($purchaseSuccessful) {
            echo "<p>Order placed successfully! Order ID: " . $_POST['product_id'] . "</p>";
        } else {
            echo "<p>Order failed to place. Please try again.</p>";
        }
    } else {
        echo "<p>Invalid product ID or quantity. Please enter numbers only.</p>";
    }
}
?>
