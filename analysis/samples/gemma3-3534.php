
</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (Important for security)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation - Add more thorough validation here!
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (
        !preg_match("/^[a-zA-Z0-9_]+$/", $username) || // Username restrictions
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $error_message = "Invalid email or username format.";
    } elseif (strlen($password) < 8) {  // Minimum password length
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            $success_message = "Registration successful!  You have been sent a verification email.";
        } else {
            // Registration failed
            $error_message = "Registration failed. Please try again.";
        }
    }
}
?>
