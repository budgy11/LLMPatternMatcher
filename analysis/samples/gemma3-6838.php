

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase process
function handlePurchase($orderData) {
    // Sanitize and validate input data
    $product_id = mysqli_real_escape_string($conn, $orderData['product_id']);
    $quantity = mysqli_real_escape_string($conn, $orderData['quantity']);
    $customer_name = mysqli_real_escape_string($conn, $orderData['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $orderData['customer_email']);
    $payment_method = mysqli_real_escape_string($conn, $orderData['payment_method']);  // e.g., "credit_card", "paypal"

    // Check if product exists
    $product_query = "SELECT id, name, price FROM products WHERE id = '$product_id'";
    $product_result = $conn->query($product_query);

    if (!$product_result->num_rows > 0) {
        return "Error: Product not found.";
    }

    $product = $product_result->fetch_assoc();
    $product_name = $product['name'];
    $product_price = $product['price'];

    // Calculate total price
    $total_price = $product_price * $quantity;

    // Insert order into the database
    $sql = "INSERT INTO orders (product_id, customer_name, customer_email, product_name, quantity, price, payment_method)
            VALUES ('$product_id', '$customer_name', '$customer_email', '$product_name', '$quantity', '$total_price', '$payment_method')";

    if ($conn->query($sql) === TRUE) {
        // Update product inventory (Assuming you have an inventory table)
        $inventory_sql = "UPDATE inventory SET quantity = quantity - '$quantity' WHERE product_id = '$product_id'";
        if ($conn->query($inventory_sql) === TRUE) {
            return "Purchase successful! Order ID: " . $conn->insert_id;
        } else {
            return "Error updating inventory.";
        }
    } else {
        return "Error creating order.";
    }
}

// ---  Example Usage (Simulating a form submission) ---

// Assuming you have a form with fields: product_id, quantity, customer_name, customer_email, payment_method

// Get form data (Simulating input)
$orderData = [
    'product_id' => 1, // Example product ID
    'quantity' => 2,
    'customer_name' => "John Doe",
    'customer_email' => "john.doe@example.com",
    'payment_method' => "credit_card"
];

// Process the purchase
$result = handlePurchase($orderData);

// Display the result
echo "<p>" . $result . "</p>";
?>
