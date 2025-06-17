
    <input type="hidden" name="purchase_submit" value="submit">
    <input type="submit" name="purchase_submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "e_commerce_db";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data (important!)
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $quantity = intval($_POST["quantity"]); // Ensure quantity is an integer
    $price = floatval($_POST["price"]); // Ensure price is a float

    // Basic validation - more validation is recommended
    if (empty($name) || empty($email) || $quantity <= 0 || $price <= 0) {
        $error = "Please fill in all fields and ensure quantity and price are positive numbers.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Process the order
        // 1.  Create a database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // 2. Prepare and execute the SQL query
        $sql = "INSERT INTO orders (name, email, quantity, price) VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssfd", $name, $email, $quantity, $price);  // "s" for string, "d" for double (float)

            if ($stmt->execute()) {
                $success_message = "Order placed successfully! Thank you for your purchase.";
            } else {
                $error = "Query execution failed: " . $stmt->error;
            }
        } else {
            $error = "Query preparation failed.";
        }

        // Close the connection
        $conn->close();
    }
}
?>
