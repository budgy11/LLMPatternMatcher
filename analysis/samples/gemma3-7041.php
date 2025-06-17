
</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerceshop";
$db_user = "your_user";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $quantity = $_POST["quantity"];
    $product_id = $_POST["product_id"]; // Assuming you have a product ID

    // Sanitize the input (VERY IMPORTANT)
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $quantity = intval($quantity); // Convert quantity to integer
    $product_id = intval($product_id); // Convert product ID to integer


    // Check if quantity is valid
    if ($quantity <= 0) {
        $error_message = "Quantity must be greater than 0.";
    } else {
        //  Database query to add the purchase to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO purchases (name, email, quantity, product_id) VALUES ('$name', '$email', '$quantity', '$product_id')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Purchase added successfully!";
        } else {
            $error_message = "Error adding purchase: " . $conn->error;
        }

        $conn->close();
    }
}
?>
