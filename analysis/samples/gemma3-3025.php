

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Check if required fields are empty
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields must be filled.";
    }
    // Email validation
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    }
    // Password complexity (Basic) -  Adjust to your requirements
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }
    // Check if the email already exists
    else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $name, $password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "<p class='success'>Registration successful. Please log in.</p>";
            // You might want to redirect to the login page here
            // header("Location: login.php");
            // exit;
        } else {
            // Registration failed
            echo "<p class='error'>Registration failed.  Error: " . $conn->error . "</p>";
        }
    }
}
?>
