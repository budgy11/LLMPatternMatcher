    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price per Unit:</label>
    <input type="number" id="price" name="price" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPassword = 'your_password';
$dbName = 'your_database_name';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle purchase
function handlePurchase($orderData) {
    $conn = connectToDatabase();

    // Prepare SQL query
    $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, order_date) 
            VALUES ('" . $conn->real_escape_string($orderData['product_id']) . "', 
                    '" . $conn->real_escape_string($orderData['quantity']) . "', 
                    '" . $conn->real_escape_string($orderData['customer_name']) . "', 
                    '" . $conn->real_escape_string($orderData['customer_email']) . "', 
                    NOW())";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Success
        return true;
    } else {
        // Error
        echo "Error: " . $conn->error;
        return false;
    }
}

//  Example Data (simulated) -  Replace with actual data from a form
$orderData = [
    'product_id' => '123',  // Example product ID
    'quantity' => '2',
    'customer_name' => 'John Doe',
    'customer_email' => 'john.doe@example.com'
];



// Handle the purchase request (e.g., from a form submission)
if (isset($_POST['submit'])) {
    // Validate the input (crucial for security - see below)
    if (empty($orderData['product_id']) || empty($orderData['quantity']) || empty($orderData['customer_name']) || empty($orderData['customer_email'])) {
        echo "<p style='color:red;'>Please fill in all fields.</p>";
    } else {
        // Process the order (call the handlePurchase function)
        if (handlePurchase($orderData)) {
            echo "<p style='color:green;'>Order placed successfully!</p>";
        }
    }
}

// Example of accessing product details (replace with your product data)
// This demonstrates how to fetch product information based on product_id
$product = [
    'product_id' => '123',
    'name' => 'Awesome Widget',
    'price' => 19.99
];
echo "<p>Product: " . $product['name'] . " - Price: $" . $product['price'] . "</p>";
?>
