
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Assuming you're using a database like MySQL or PostgreSQL
// This is a simplified example, replace with your actual database connection

// Database credentials (replace with your own)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input (important for security)
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (preg_match("/@/", $email)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Hash the password (VERY IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($GLOBALS["conn"], $sql)) {
            $success = "Registration successful! Please check your email to activate your account.";
        } else {
            $error = "Registration failed. " . mysqli_error($GLOBALS["conn"]);
        }
    }
}

// Database connection (using MySQLi - a better option than mysqli_connect)
$GLOBALS["conn"] = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($GLOBALS["conn"]->connect_error) {
    die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}

?>
