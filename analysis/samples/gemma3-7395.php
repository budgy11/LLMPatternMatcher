

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce_db";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (Important for security)
    $name = $_POST["name"];
    $email = $_POST["email"];
    $quantity = (int)$_POST["quantity"];  // Convert to integer
    $price = (float)$_POST["price"];      // Convert to float
    $address = $_POST["address"];

    // Sanitize input (Example - more robust sanitization might be needed)
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $address = htmlspecialchars($address);

    // Check if required fields are filled
    if (empty($name) || empty($email) || empty($quantity) || empty($price) || empty($address)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($quantity <= 0 || $price <= 0) {
        $error = "Quantity and Price must be greater than zero.";
    } else {
        //  Simulate a purchase confirmation
        $total = $quantity * $price;
        $confirmation_message = "Your purchase is confirmed! Total: $" . $total;

        // Store the order data (This is a basic example. In a real application,
        //  you would store the order in a database)
        $order_data = array(
            "name" => $name,
            "email" => $email,
            "quantity" => $quantity,
            "price" => $price,
            "address" => $address,
            "total" => $total,
            "timestamp" => date("Y-m-d H:i:s")
        );

        //  For demonstration, display the order data.  In a real app, store this in DB
        echo "<p>Order Details:</p>";
        echo "<pre>";
        print_r($order_data);
        echo "</pre>";

        echo "<p>" . $confirmation_message . "</p>";
    }
}
?>
