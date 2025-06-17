

<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs (VERY IMPORTANT - Prevent SQL Injection)
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate inputs
    $username = trim($username); // Remove leading/trailing whitespace
    $email = trim($email);
    $password = trim($password);


    // Check if username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else if (strlen($password) < 6) {  // Example:  Password must be at least 6 characters long
        $error_message = "Password must be at least 6 characters long.";
    }
    else {
        // Hash the password - **CRITICAL SECURITY STEP**
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ss", $username, $email, $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                // Registration successful
                echo "Registration successful!  You have been redirected.";
                // Redirect to login page or homepage
                header("Location: login.php");
                exit;
            } else {
                // Handle query execution errors
                echo "Query execution failed: " . $stmt->error;
            }
        } else {
            // Handle statement preparation errors
            echo "Error preparing statement.";
        }
    }
}

// Display error messages if any
if (isset($error_message)) {
    echo "<p style='color:red;'>$error_message</p>";
}

?>
