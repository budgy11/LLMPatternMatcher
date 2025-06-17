

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Validation (Implement more robust validation as needed)
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (preg_match("/@/", $email)) {
        $error = "Invalid email format.";
    } else {
        // Hash the password (important for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data into the database
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password, $email);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "<p style='color: green;'>Registration successful. Please login.</p>";
        } else {
            // Registration failed
            echo "<p style='color: red;'>Registration failed: " . $stmt->error . "</p>";
        }
    }
}
?>
