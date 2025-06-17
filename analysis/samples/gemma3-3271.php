    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate input data
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);

    // Check if username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if username is already registered
        $sql_check_username = "SELECT id, username, email FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql_check_username);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username already exists.";
        } else {
            // Check if email is valid format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            }
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $success = "Registration successful.  Please check your email to verify your account.";
            } else {
                $error = "Registration failed. " . mysqli_error($conn);
            }
        }
    }
}
?>
