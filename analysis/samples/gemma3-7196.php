<?php endif; ?>

</body>
</html>


<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs (important for security)
    $product_name = htmlspecialchars($_POST["product_name"]);
    $quantity = intval($_POST["quantity"]);
    $total_price = floatval($_POST["total_price"]); // Handle decimals correctly

    if (empty($product_name) || $quantity <= 0 || $total_price <= 0) {
        $error_message = "Please fill in all fields and ensure quantity and total price are positive values.";
    } else {
        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO products (product_name, quantity, total_price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sii", $product_name, $quantity, $total_price);

            // Execute the statement
            if ($stmt->execute()) {
                $success_message = "Product added successfully!";
            } else {
                $error_message = "Error adding product: " . $stmt->error;
            }
        } else {
            $error_message = "Error preparing statement.";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
