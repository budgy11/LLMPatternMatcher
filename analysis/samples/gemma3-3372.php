    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_pass = 'your_password';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data (basic example - extend this for production)
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Input validation (very basic - you need to improve this)
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    }
    else {
        // Hash the password (VERY IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user into the database
        $sql = "INSERT INTO users (username, email, password)
                VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($GLOBALS['conn'], $sql)) {
            $success = "Registration successful! Please check your email to confirm.";
        } else {
            $error = "Error registering. " . mysqli_error($GLOBALS['conn']);
        }
    }
}

// Establish database connection
$GLOBALS['conn'] = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$GLOBALS['conn']) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
