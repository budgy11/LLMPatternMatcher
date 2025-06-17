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

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate input
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);

    // Check if username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        // Check if username already exists
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($GLOBALS['db_host'], $sql); // Use $GLOBALS for consistent db connection

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Username already exists.";
        } else {
            // Check if email is valid format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Invalid email format.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user into the database
                $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
                mysqli_query($GLOBALS['db_host'], $sql);

                // Success message
                $success_message = "Registration successful! Please check your email to verify your account.";
            }
        }
    }
}
?>
