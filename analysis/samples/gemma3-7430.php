
</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Add this to prevent SQL injection. Use prepared statements!
// This is a VERY basic example and should be expanded for production code.
// It's crucial to use prepared statements with real-world applications.
// This example assumes you're using MySQLi.  Adjust accordingly if using PDO.

// Function to connect to the database
function connectToDatabase() {
    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $address) {
    $mysqli = connectToDatabase();

    // Validate inputs (basic example - expand for more robust validation)
    if (empty($cart_id) || empty($customer_name) || empty($customer_email) || empty($address)) {
        return "Error: All fields are required.";
    }

    // ---  Data Sanitization and Validation (Crucial!) ---
    //  Never trust user input. Sanitize and validate *everything*.
    //  Example:  Validate email format, numeric input, etc.

    // Create the SQL query
    $query = "INSERT INTO orders (cart_id, customer_name, customer_email, address, order_date)
              VALUES ('$cart_id', '$customer_name', '$customer_email', '$address', NOW())";

    if ($mysqli->query($query) === TRUE) {
        // Optionally, update the cart_items table to mark items as purchased
        // Example:  $mysqli->query("UPDATE cart_items SET purchased = 1 WHERE cart_id = '$cart_id'")

        return "Purchase successful! Order ID: " . $mysqli->insert_id;
    } else {
        return "Error: " . $mysqli->error;
    }
}

// ------------------- Example Usage (Simulated) -------------------

// Simulated Cart Data (Replace with your actual cart retrieval)
$cart_id = 123;
$customer_name = "John Doe";
$customer_email = "john.doe@example.com";
$address = "123 Main St, Anytown, USA";

// Process the purchase
$result = handlePurchase($cart_id, $customer_name, $customer_email, $address);

echo $result; // Output the result of the purchase

?>
