

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (basic validation - improve this for production)
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $quantity = intval($_POST["quantity"]);
    $product_id = intval($_POST["product_id"]);

    // Check if required fields are filled
    if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
        $error = "Please fill in all the fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($quantity <= 0) {
        $error = "Quantity must be a positive number.";
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO orders (name, email, quantity, product_id) 
                VALUES ('$name', '$email', $quantity, $product_id)";

        // Execute the query
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            $success = "Order placed successfully! Order ID: " . $conn->insert_id;
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the connection
        $conn->close();
    }
}
?>
