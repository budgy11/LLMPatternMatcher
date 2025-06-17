

<?php
session_start();

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $address) {
    $conn = connectToDatabase();

    // Sanitize inputs (Important for security)
    $customer_name = $conn->real_escape_string($customer_name);
    $customer_email = $conn->real_escape_string($customer_email);
    $address = $conn->real_escape_string($address);

    // Insert order information into the database
    $order_query = "INSERT INTO orders (customer_name, customer_email, address, order_date) VALUES ('$customer_name', '$customer_email', '$address', NOW())";
    if ($conn->query($order_query) === TRUE) {
        $order_id = $conn->insert_id; // Get the ID of the newly created order
        echo "Order placed successfully! Order ID: " . $order_id . "<br>";

        // Update the cart (mark items as purchased)
        $update_cart_query = "UPDATE carts SET purchased = 1 WHERE id = '$cart_id'";
        if ($conn->query($update_cart_query) === TRUE) {
            echo "Cart updated successfully.<br>";
        } else {
            echo "Error updating cart: " . $conn->error . "<br>";
        }

    } else {
        echo "Error placing order: " . $conn->error . "<br>";
    }
}


// ---  Example Usage / Handling the Purchase Request ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form has been submitted
    if (isset($_POST['submit_order'])) {
        // Get cart ID from the form
        $cart_id = $_POST['cart_id'];

        // Get customer information from the form
        $customer_name = $_POST['customer_name'];
        $customer_email = $_POST['customer_email'];
        $address = $_POST['address'];


        // Call the handlePurchase function to process the order
        handlePurchase($cart_id, $customer_name, $customer_email, $address);
    }
}


// --------------------------------------------------
//  HTML Form for the Purchase Request
?>
