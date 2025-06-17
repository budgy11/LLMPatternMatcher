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

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the registration form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Sanitize input data
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);

    // Check if required fields are filled
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        // Password validation (basic example - consider stronger methods)
        if (strlen($password) < 8) {
            $error_message = "Password must be at least 8 characters long.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL query to insert the new user into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

            // Execute the query
            $result = mysqli_query($GLOBALS['db_host'], $sql);

            if (mysqli_error($GLOBALS['db_host'])) {
                $error_message = "Error inserting user: " . mysqli_error($GLOBALS['db_host']);
            } else {
                $success_message = "Registration successful! Please check your email for verification instructions.";
            }
        }
    }
}
?>
