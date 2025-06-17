

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Error reporting (optional, for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

//  ----- Function to connect to the database -----
function connectToDatabase() {
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// ----- Purchase Function -----
function processPurchase($orderData) {
    $conn = connectToDatabase();

    // Sanitize and validate the input data
    $product_id = mysqli_real_escape_string($conn, $orderData['product_id']);
    $quantity = mysqli_real_escape_string($conn, $orderData['quantity']);
    $customer_name = mysqli_real_escape_string($conn, $orderData['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $orderData['customer_email']);

    // Validate quantity (ensure it's a positive integer)
    if (!is_numeric($quantity) || $quantity <= 0) {
        return "Invalid quantity. Please enter a positive integer.";
    }

    // Check if the product exists
    $product_query = "SELECT id, name, price FROM products WHERE id = '$product_id'";
    $product_result = $conn->query($product_query);

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        $product_name = $product['name'];
        $product_price = $product['price'];
    } else {
        return "Product ID not found.";
    }

    // Calculate the total price
    $total_price = $product_price * $quantity;

    // Construct the SQL query
    $sql = "INSERT INTO orders (product_id, customer_name, customer_email, quantity, total_price)
            VALUES ('$product_id', '$customer_name', '$customer_email', '$quantity', '$total_price')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        return "Purchase successful! Order ID: " . $conn->insert_id;
    } else {
        return "Error processing purchase: " . $conn->error;
    }
}

// ----- Example Usage (Simulating a form submission) -----
// Simulate form data
$orderData = [
    'product_id' => '1',
    'customer_name' => 'John Doe',
    'customer_email' => 'john.doe@example.com',
    'quantity' => 2,
];

// Process the purchase
$result = processPurchase($orderData);
echo $result;

?>
