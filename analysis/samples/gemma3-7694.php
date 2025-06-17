

<?php

// Database Configuration (Replace with your actual details)
$dbHost = "localhost";
$dbUsername = "your_username";
$dbPassword = "your_password";
$dbName = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize input (IMPORTANT!)
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $quantity = intval($_POST["quantity"]);
    $product_id = intval($_POST["product_id"]);  // Assuming you have a product_id

    // Validation (Basic - improve as needed)
    if (empty($name) || empty($email) || $quantity <= 0 || $product_id <= 0) {
        $error = "Please fill in all fields correctly.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Prepare SQL query (Important for security - use prepared statements)
        $sql = "INSERT INTO orders (name, email, quantity, product_id) VALUES (?, ?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql); // Assuming $conn is your database connection

        // Bind parameters (Prevent SQL injection)
        $stmt->bind_param("sii", $name, $email, $quantity);

        // Execute query
        if ($stmt->execute()) {
            $success = "Order placed successfully! Order ID: " . $conn->insert_id;
        } else {
            $error = "Error placing order: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Database connection (Establish connection)
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
