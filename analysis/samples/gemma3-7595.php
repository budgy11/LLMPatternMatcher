

<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate input
    $product_name = trim($_POST["product_name"]);
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);
    $customer_name = trim($_POST["customer_name"]);
    $customer_email = trim($_POST["customer_email"]);


    // Basic validation
    if (empty($product_name) || empty($quantity) || empty($price) || empty($customer_name) || empty($customer_email)) {
        $error_message = "Please fill in all fields.";
    } elseif ($quantity <= 0 || $price <= 0) {
        $error_message = "Quantity and Price must be greater than zero.";
    } else {
        // Construct the SQL query
        $sql = "INSERT INTO purchases (product_name, quantity, price, customer_name, customer_email)
                VALUES ('$product_name', $quantity, $price, '$customer_name', '$customer_email')";

        // Execute the query
        if (mysqli_query($db_connection, $sql)) {
            $success_message = "Purchase successful! Thank you, $customer_name, for your order.";
        } else {
            $error_message = "Error: " . mysqli_error($db_connection);
        }
    }
} else {
    // If the form hasn't been submitted, initialize the form data
    $product_name = "";
    $quantity = 0;
    $price = 0.0;
    $customer_name = "";
    $customer_email = "";
    $error_message = "";
    $success_message = "";
}


// Connect to the database
$db_connection = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// ---  HTML Form for the Purchase ---
?>
