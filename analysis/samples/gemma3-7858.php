

<?php

// Database Configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs (IMPORTANT for security!)
    $product_name = htmlspecialchars($_POST["product_name"]);
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);

    // Check if inputs are valid
    if (empty($product_name) || $quantity <= 0 || $price <= 0) {
        $error_message = "Please fill in all fields and enter valid quantities and prices.";
    } else {
        // Create a database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the SQL query
        $sql = "INSERT INTO purchases (product_name, quantity, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssi", $product_name, $quantity, $price); // "ssi" indicates string, string, integer
            if ($stmt->execute()) {
                $success_message = "Purchase added successfully!";
            } else {
                $error_message = "Error adding purchase: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Error preparing statement.";
        }

        $conn->close();
    }
}
?>
