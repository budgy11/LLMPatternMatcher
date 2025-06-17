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

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate input (Crucial for security)
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Hash the password (Never store passwords in plain text!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($GLOBALS["conn"], $sql)) {
            // Registration successful
            $success = true;
        } else {
            // Registration failed
            $error = "Error registering user. " . mysqli_error($GLOBALS["conn"]);
        }
    }
} else {
    // If the form hasn't been submitted, clear any existing errors
    $error = "";
    $success = false;
}

// Connect to the database (Establish the connection once and reuse it)
$GLOBALS["conn"] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$GLOBALS["conn"]) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>
