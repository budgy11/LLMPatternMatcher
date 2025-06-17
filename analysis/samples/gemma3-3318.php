    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Form handling (for simplicity, this assumes form data is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent security vulnerabilities
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate input
    $username = trim($username); // Remove leading/trailing whitespace
    $email = trim($email);
    $password = trim($password);

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the password (VERY IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);  // Use prepared statements to prevent SQL injection

        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param("ss", $username, $email, $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                $success = "Registration successful. Please check your email for confirmation.";
            } else {
                $error = "Registration failed. " . $stmt->error;
            }
        } else {
            $error = "Error preparing statement.";
        }
    }
}

// Database connection (replace with your connection details)
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
