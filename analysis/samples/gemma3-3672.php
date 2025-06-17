    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);


    // Check if username and email are required
    if (empty($username) || empty($email)) {
        $error = "Username and email are required.";
    }
    // Password validation - Minimal example, extend for more robust validation
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }
    // Check if the email already exists
    else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error = "Email address is already registered.";
        }
        // If no error, insert the new user
        else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT); // Secure password hashing

            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";

            if (mysqli_query($db_host, $sql)) {
                // Registration successful
                echo "<p style='color:green;'>Registration successful! Please <a href='login.php'>login</a>.</p>";
            } else {
                // Registration failed
                echo "<p style='color:red;'>Error: " . mysqli_error($db_host) . "</p>";
            }
        }
    }
}
?>
