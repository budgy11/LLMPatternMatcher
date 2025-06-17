                </select>
            <button type="submit" name="action" value="remove_from_cart">Remove from Cart</button>
        </form>
    </div>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = 'localhost';
$db_name = 'e_commerce_db';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to handle purchase process
function handlePurchase($cart, $customer_name, $customer_email, $address, $payment_method) {
    $conn = connectToDatabase();

    // 1. Insert Order Information
    $query = "INSERT INTO orders (customer_name, customer_email, address, order_date, payment_method) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssis", $customer_name, $customer_email, $payment_method);
    if (!$stmt->execute()) {
        echo "Error inserting order: " . $stmt->error;
        return false;
    }
    $order_id = $conn->insert_id; // Get the newly created order ID

    // 2. Insert Order Items
    foreach ($cart as $item_id => $quantity) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $order_id, $item_id, $quantity);
        if (!$stmt->execute()) {
            echo "Error inserting order item: " . $stmt->error;
            // You might want to handle this more robustly, like rolling back the order
            return false;
        }
    }

    // 3. Update Product Stock (Optional - depends on your requirements)
    // This is a basic example. You'd likely want to handle concurrency and potential errors.
    // This example assumes a simple update, which may not be suitable for high-volume sales.
    foreach ($cart as $item_id => $quantity) {
        $query = "UPDATE products SET stock = stock - ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $quantity, $item_id);
        if (!$stmt->execute()) {
            echo "Error updating product stock: " . $stmt->error;
            // Handle error appropriately
            return false;
        }
    }


    // 4.  Confirmation Message
    echo "<h1>Purchase Successful!</h1>";
    echo "<p>Order ID: " . $order_id . "</p>";
    echo "<p>Thank you for your purchase!</p>";

    return true;
}


// --- Example Usage (Simulated Form Submission) ---
//  This is how you'd handle a form submission.  In a real application, you'd
//  process the form data using a server-side language like PHP, and
//  use appropriate input validation and sanitization.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simulate cart data (replace with data from your shopping cart)
    $cart = [
        1 => 2, // Product ID 1, Quantity 2
        2 => 1, // Product ID 2, Quantity 1
        3 => 3  // Product ID 3, Quantity 3
    ];

    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $address = $_POST["address"];
    $payment_method = $_POST["payment_method"];

    if (handlePurchase($cart, $customer_name, $customer_email, $address, $payment_method)) {
        // Handle successful purchase (e.g., redirect, send confirmation email)
        echo "<p>Order submitted successfully!</p>";
    } else {
        // Handle purchase failure
        echo "<p>Purchase failed. Please try again.</p>";
    }
}
?>
