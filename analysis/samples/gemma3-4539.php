    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input (Important for security)
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Hash the password (VERY IMPORTANT - never store passwords in plain text)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ss", $username, $hashed_password);

            // Execute query
            if ($stmt->execute()) {
                $success = "Registration successful! Please check your email for verification.";
            } else {
                $error = "Registration failed: " . $stmt->error;
            }
        } else {
            $error = "Error preparing statement.";
        }
        $stmt->close();
    }
}
?>
