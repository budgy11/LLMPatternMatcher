

<?php

// Database connection details (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form data handling (assuming the form is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate password strength (basic example)
    if (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Check if username is empty
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    }
    
    // Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if all validations pass
    if (empty($username_error) && empty($email_error) && empty($password_error)) {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement (use prepared statements to prevent SQL injection)
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!  You have been redirected.";
            // Redirect the user (optional)
            header("Location: login.php"); // Or your desired page
            exit();
        } else {
            // Query failed
            echo "Query failed: " . $stmt->error;
        }
    }
}
?>
