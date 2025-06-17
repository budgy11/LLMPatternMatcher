

<?php

// Database Configuration (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and Validate Input (Crucial for Security!)
    $product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : 0;
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

    // Database Connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and Execute the SQL Query
    $sql = "INSERT INTO orders (product_id, quantity) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $product_id, $quantity);  // "ii" indicates two integers
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows > 0) {
        echo "<p>Order placed successfully! Order ID: " . $conn->insert_id . "</p>";
    } else {
        echo "<p>Order failed.  Check your product ID and quantity.</p>";
    }

    $stmt->close();
    $conn->close();
}

?>
