</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function handlePurchase($cartItems, $customerName, $customerEmail, $customerAddress) {
    $conn = connectToDatabase();

    // Insert order into the database
    $sql = "INSERT INTO orders (customer_name, customer_email, customer_address, order_date)
            VALUES ('" . $conn->real_escape_string($customerName) . "',
                    '" . $conn->real_escape_string($customerEmail) . "',
                    '" . $conn->real_escape_string($customerAddress) . "',
                    NOW())";

    if (!$conn->query($sql)) {
        echo "Error inserting order: " . $conn->error;
        return false;
    }

    $orderId = $conn->insert_id; // Get the last inserted order ID

    // Insert order items into the order_items table
    foreach ($cartItems as $item) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity)
                VALUES (" . $conn->real_escape_string($orderId) . ",
                        " . $conn->real_escape_string($item['product_id']) . ",
                        " . $conn->real_escape_string($item['quantity']) . ")";

        if (!$conn->query($sql)) {
            echo "Error inserting order item: " . $conn->error;
            // Handle the error appropriately (e.g., logging, redirecting)
            return false; // Consider returning false on error
        }
    }

    // Update the product quantity in the products table (Decrement Quantity)
    foreach ($cartItems as $item) {
        $sql = "UPDATE products
                SET quantity = quantity - " . $item['quantity'] . "
                WHERE id = " . $item['product_id'];

        if (!$conn->query($sql)) {
            echo "Error updating product quantity: " . $conn->error;
            // Handle the error appropriately
            return false;
        }
    }


    echo "Purchase successful! Order ID: " . $orderId;
    return true;
}


// Example usage (This part would be in your form handling code)
// Assuming you have a form that collects customer data and cart items
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST["customer_name"];
    $customerEmail = $_POST["customer_email"];
    $customerAddress = $_POST["customer_address"];

    $cartItems = array();
    if (isset($_POST['cart_items'])) {
        $cartItems = $_POST['cart_items'];
    }

    // Handle the purchase
    if (handlePurchase($cartItems, $customerName, $customerEmail, $customerAddress)) {
        // Redirect to a success page or display a success message
        echo "<p>Your order has been placed successfully!</p>";
    } else {
        // Handle purchase failure (e.g., display an error message)
        echo "<p>An error occurred during the purchase.</p>";
    }
}


// Example HTML Form (for demonstration - place this in your HTML)
?>
