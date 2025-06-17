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
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling (assuming it's submitted via POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input - VERY IMPORTANT for security!
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate input
    $username = trim($username);  // Remove leading/trailing whitespace
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_VALIDATE_REGULAR); //Stronger password validation

    // Check if the email and password are required
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = "Invalid email address!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }
    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['conn'], $sql); // Use $GLOBALS['conn'] for database connection

    if (mysqli_num_rows($result) > 0) {
        $error = "Username already exists!";
    }

    // If no errors, insert the user into the database
    if (empty($error)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Secure password hashing
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($GLOBALS['conn'], $sql)) {
            $success = "Registration successful! Please log in.";
        } else {
            $error = "Error inserting data into the database. " . mysqli_error($GLOBALS['conn']);
        }
    }
}
?>
