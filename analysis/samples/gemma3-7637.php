
</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_password = 'your_db_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle the purchase form submission
function handlePurchaseForm($product_id, $quantity, $customer_name, $customer_email) {
    $conn = connectToDatabase();

    // Prepare the SQL query
    $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, order_date) 
            VALUES ('$product_id', '$quantity', '$customer_name', '$customer_email', NOW())";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (Important for security - add more validation as needed)
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0; // Convert to integer
    $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
    $customer_email = isset($_POST['customer_email']) ? $_POST['customer_email'] : '';

    // Perform validation (example - check if product_id is valid)
    if (empty($product_id) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
        echo "<p style='color: red;'>Please fill in all fields.</p>";
    } else {
        // Call the function to handle the purchase
        handlePurchaseForm($product_id, $quantity, $customer_name, $customer_email);
    }
}

?>
