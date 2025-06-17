

<?php

// Database configuration (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    }
    // Validate the password (basic check - can be enhanced)
    if (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    }

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $firstname, $lastname, $hashed_password);


    // Execute the query
    if ($stmt->execute()) {
        // Registration successful
        $success_message = "Registration successful! Please check your email to activate your account.";
    } else {
        // Registration failed
        $error_message = "Registration failed.  " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

?>
