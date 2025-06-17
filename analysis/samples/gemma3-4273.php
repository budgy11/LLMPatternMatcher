    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation (Basic - enhance as needed)
    if (empty($username) || empty($email) || empty($password)) {
        $errors = "All fields are required.";
    } elseif (
        !preg_match("/^[a-zA-Z0-9._-]+$/", $username) || // Username validation
        !preg_match("/^[^\@]+@[^\@]+\.[^\@]+$/", $email) || // Email validation
        $password < 6 // Minimum password length
    ) {
        $errors = "Invalid username, email, or password.";
    } else {
        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please <a href='login.php'>login</a>.";
        } else {
            // Registration failed
            echo "Registration failed: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}
?>
