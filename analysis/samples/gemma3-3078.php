
</body>
</html>


<?php

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize input (IMPORTANT!)
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Password validation (basic example - enhance as needed)
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            $success_message = "Registration successful.  Please check your email to verify.";
        } else {
            // Handle errors
            echo "Error: " . $stmt->error; // More detailed error handling is recommended
            $error_message = "Registration failed.  Check database connection or constraints.";
        }
    }
}
?>
