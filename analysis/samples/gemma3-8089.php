
<!-- HTML Form (Simplified example - you'll need to build this out properly) -->
<form method="post">
  <label for="product_id">Product ID:</label>
  <input type="number" id="product_id" name="product_id" required>
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity" required>
  <button type="submit" name="action" value="addToCart">Add to Cart</button>
</form>

<form method="post">
  <label for="product_id">Product ID to Remove:</label>
  <input type="number" id="product_id" name="product_id" required>
  <button type="submit" name="action" value="removeFromCart">Remove from Cart</button>
</form>


<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase request
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
    $conn = connectToDatabase();

    // 1. Retrieve cart details
    $sql_cart = "SELECT product_id, quantity FROM cart WHERE cart_id = '$cart_id'";
    $result = $conn->query($sql_cart);

    if ($result->num_rows > 0) {
        $cart_data = $result->fetch_assoc();
        $product_id = $cart_data['product_id'];
        $quantity = $cart_data['quantity'];

        // 2. Update inventory (Adjust this based on your inventory system)
        // Example:  Simple inventory update (modify for your database)
        $sql_update_inventory = "UPDATE products SET stock = stock - '$quantity' WHERE id = '$product_id'";
        if (!$conn->query($sql_update_inventory)) {
            echo "Error updating inventory: " . $conn->error;
            return false;
        }

        // 3. Record the order
        $sql_insert_order = "INSERT INTO orders (cart_id, customer_name, customer_email, order_date, payment_method)
                             VALUES ('$cart_id', '$customer_name', '$customer_email', NOW(), '$payment_method')";

        if (!$conn->query($sql_insert_order)) {
            echo "Error inserting order: " . $conn->error;
            return false;
        }

        // 4. Clear the cart (optional, depending on your requirements)
        $sql_clear_cart = "DELETE FROM cart WHERE cart_id = '$cart_id'";
        if (!$conn->query($sql_clear_cart)) {
            echo "Error clearing cart: " . $conn->error;
            return false;
        }

        return true; // Purchase successful
    } else {
        return false; // Cart not found
    }
}

// ------------------ Example Usage (Simulated) ------------------

// Assuming $cart_id is the ID of the cart to purchase
// $cart_id = "123"; // Replace with the actual cart ID

// Simulate form data (replace with actual form processing)
$cart_id = $_POST['cart_id'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$payment_method = $_POST['payment_method'];

//  Validate input (VERY IMPORTANT!  Add more robust validation here)
if (empty($cart_id) || empty($customer_name) || empty($customer_email) || empty($payment_method)) {
    echo "Error: All fields are required.";
} else {
    // Handle the purchase
    if (handlePurchase($cart_id, $customer_name, $customer_email, $payment_method)) {
        echo "Purchase successful!  Order ID: " . rand(1000, 9999); // Simulate order ID
    } else {
        echo "Purchase failed.";
    }
}

?>
