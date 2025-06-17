    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }

    // Hash the password (IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $username_error = "Username already exists.";
    }

    // Insert the user into the database
    if (empty($username_error) && empty($email_error)) {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($db_host, $sql)) {
            $registration_success = true;
            $registration_message = "Registration successful! You have been logged in.";
        } else {
            $registration_error = "Error registering user. Please try again later.";
        }
    }
}

?>
