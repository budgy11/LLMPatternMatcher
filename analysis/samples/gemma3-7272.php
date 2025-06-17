
</body>
</html>


<?php

// Configuration (Database connection details - ADJUST THESE!)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input (Important for security!)
    $firstName = trim($_POST["firstName"]);
    $lastName = trim($_POST["lastName"]);
    $email = trim($_POST["email"]);
    $quantity = intval($_POST["quantity"]); // Convert to integer
    $price = floatval($_POST["price"]); // Convert to float

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || $quantity <= 0 || $price <= 0) {
        $error = "Please fill in all fields correctly.";
    } else {
        // Sanitize inputs (basic - improve as needed for production)
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Database connection
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO purchases (firstName, lastName, email, quantity, price) VALUES ('$firstName', '$lastName', '$email', $quantity, $price)";

        if ($conn->query($sql)) {
            $success = "Purchase added successfully!";
        } else {
            $error = "Error adding purchase: " . $conn->error;
        }

        $conn->close();
    }
}
?>
