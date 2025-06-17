    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price:</label>
    <input type="number" step="0.01" id="price" name="price" required><br><br>

    <button type="submit">Add to Cart</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_user";
$dbPass = "your_password";
$dbName = "your_database";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function handlePurchase($orderData) {
    $conn = connectToDatabase();

    // Sanitize and validate the input data
    $product_id = $conn->real_escape_string($orderData['product_id']);
    $quantity = $conn->real_escape_string($orderData['quantity']);
    $customer_name = $conn->real_escape_string($orderData['customer_name']);
    $customer_email = $conn->real_escape_string($orderData['customer_email']);

    // Check if product exists (basic example - improve with more robust validation)
    $check_product_query = "SELECT id, name, price FROM products WHERE id = '$product_id'";
    $result = $conn->query($check_product_query);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_name = $product['name'];
        $product_price = $product['price'];

        // Calculate the total price
        $total_price = $product_price * $quantity;

        // Insert the order into the database
        $order_query = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, total_price)
                        VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email', '$total_price')";

        if ($conn->query($order_query) === TRUE) {
            // Optionally, update the product inventory (example)
            //  $inventory_update_query = "UPDATE products SET stock = stock - $quantity WHERE id = '$product_id'";
            //  $conn->query($inventory_update_query);

            return true; // Purchase successful
        } else {
            echo "Error inserting order: " . $conn->error;
            return false;
        }
    } else {
        echo "Product with ID '$product_id' not found.";
        return false;
    }

    $conn->close();
}

// ------------------ Example Usage (from a form submission or API) ------------------

// Assuming you received the order data from a POST request
// (e.g., from an HTML form or an API call)

// Example Data (replace with actual form data)
$orderData = [
    'product_id' => '1',
    'quantity' => '2',
    'customer_name' => 'John Doe',
    'customer_email' => 'john.doe@example.com'
];

// Process the purchase
$purchaseSuccessful = handlePurchase($orderData);

if ($purchaseSuccessful) {
    echo "Purchase successful! Order ID: [Generated Order ID Here]";
} else {
    echo "Purchase failed.  Check the error messages above.";
}

?>
