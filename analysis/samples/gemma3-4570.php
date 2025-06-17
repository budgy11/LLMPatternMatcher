    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data (Important for security)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation - Add more robust validation as needed
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data into the database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($GLOBALS['conn'], $sql)) {
            $success_message = "Registration successful! Please check your email to activate your account.";
        } else {
            $error_message = "Error: " . mysqli_error($GLOBALS['conn']);
        }
    }
}

// Connect to the database
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
