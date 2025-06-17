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

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate input data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate required fields
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        // Validate email format (basic)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format.";
        }
        // Validate password length (example - adjust as needed)
        if (strlen($password) < 8) {
            $error_message = "Password must be at least 8 characters long.";
        }

        // Check if username already exists (basic - adjust as needed)
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($GLOBALS["db_host"], $sql);
        if (mysqli_num_rows($result) > 0) {
            $error_message = "Username already exists.";
        }

        // If no errors, proceed with registration
        if (empty($error_message)) {
            // Hash the password (important for security)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL query to insert the new user
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

            // Execute the query
            mysqli_query($GLOBALS["db_host"], $sql);

            // Redirect to a success page (or display a success message)
            header("Location: success.php"); // Replace with your success page
            exit();
        }
    }
}
?>
