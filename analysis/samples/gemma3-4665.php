    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Sanitize and validate input (Crucial for security!)
    $username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_STRIP);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check if username and email are empty
    if (empty($username) || empty($email)) {
        $error_message = "Username and Email are required.";
    } elseif (strlen($username) < 3) {
        $error_message = "Username must be at least 3 characters long.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error_message = "Invalid Email Address.";
    } else {
        // Hash the password (Important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($GLOBALS['conn'], $sql)) {
            $success_message = "Registration successful!  You have been logged in.";
        } else {
            $error_message = "Error: " . mysqli_error($GLOBALS['conn']);
        }
    }
} else {
    // Initialize variables for the registration form
    $error_message = "";
    $success_message = "";
}

// Connect to the database
$GLOBALS['conn'] = mysqli_connect($host, $username, $password, $database);

// Check connection
if ($GLOBALS['conn']->connect_error) {
    die("Connection failed: " . $GLOBALS['conn']->connect_error);
}

?>
