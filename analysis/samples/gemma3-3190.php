        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form handling (validate and sanitize inputs)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate inputs
    $username = trim($username); // Remove leading/trailing whitespace
    $email = trim($email);
    $password = trim($password);

    // Check if username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $error = "Username must be between 3 and 20 characters and can only contain letters, numbers, and underscores.";
    } else if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }
    // If no errors, proceed with registration
    else {
        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "<p style='color:green;'>Registration successful! You have been redirected.</p>";
        } else {
            // Registration failed
            echo "<p style='color:red;'>Registration failed. " . $conn->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    }
}
?>
