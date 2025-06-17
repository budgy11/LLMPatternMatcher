

<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function processPurchase($cart_id, $customer_name, $customer_email, $address, $payment_method) {
    // 1. Establish Database Connection
    $conn = connectToDatabase();

    // 2. Get Cart Details
    $query = "SELECT product_id, quantity, product_price FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart_data[] = $row;
        }
    }
    $stmt->close();


    // 3. Calculate Total Amount
    $total_amount = 0;
    foreach ($cart_data as $item) {
        $total_amount += $item['quantity'] * $item['product_price'];
    }

    // 4. Insert Order into the database
    $query = "INSERT INTO orders (cart_id, customer_name, customer_email, order_date, total_amount, payment_method) VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $cart_id, $customer_name, $customer_email, $total_amount, $payment_method);
    if ($stmt->execute()) {
        $order_id = $conn->insert_id; // Get the newly created order ID
        echo "Order placed successfully! Order ID: " . $order_id;
    } else {
        echo "Error placing order: " . $stmt->error;
    }
    $stmt->close();

    // 5. Update Cart (Important: Mark items as sold out)
    foreach ($cart_data as $item) {
        // Assuming you have a 'products' table with product_id and stock
        $query = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $item['quantity'], $item['product_id']);
        if ($stmt->execute()) {
            //echo "Product " . $item['product_id'] . " updated successfully.
";
        } else {
            // Handle update error (e.g., log it, display a message)
            echo "Error updating product " . $item['product_id'] . ": " . $stmt->error;
        }
        $stmt->close();
    }

    // 6. Clear the Cart (This is crucial to avoid duplicate orders)
    $query = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->close();


    // 7. Close the database connection
    $conn->close();
}


// --- Example Usage (Simulated Form Data) ---
// In a real application, this would come from a form submission.
$cart_id = 1; // Example Cart ID
$customer_name = "John Doe";
$customer_email = "john.doe@example.com";
$address = "123 Main Street, Anytown";
$payment_method = "Credit Card";


// Call the function to process the purchase
processPurchase($cart_id, $customer_name, $customer_email, $address, $payment_method);

?>
