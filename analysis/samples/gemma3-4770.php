    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate the input data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }

    // Validate password strength (basic example - customize as needed)
    if (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Check if username and email are empty
    if (empty($username) || empty($email)) {
        $username_error = "Username and email are required.";
        $email_error = "Email is required.";
    }

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // If validation passes, insert the user into the database
    if (empty($username_error) && empty($email_error) && empty($password_error)) {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // User registration successful
            echo "Registration successful! Please log in.";
        } else {
            // Registration failed
            echo "Registration failed: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}
?>
