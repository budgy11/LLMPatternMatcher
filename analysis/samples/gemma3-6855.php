
</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs (add more validation as needed)
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $quantity = intval($_POST["quantity"]);  // Ensure quantity is an integer
    $item_name = trim($_POST["item_name"]); // Name of the item purchased

    // Sanitize inputs (important to prevent security vulnerabilities)
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $item_name = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);


    // Check if required fields are empty
    if (empty($name) || empty($email) || empty($quantity) || empty($item_name)) {
        $error = "Please fill in all the fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO purchases (name, email, quantity, item_name) VALUES (?, ?, ?, ?)";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ssii", $name, $email, $quantity, $item_name);

            // Execute the statement
            if ($stmt->execute()) {
                $success = "Purchase successful! Thank you, " . $name . "!";
            } else {
                $error = "Query failed: " . $stmt->error;
            }
        } else {
            $error = "Error preparing statement.";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
