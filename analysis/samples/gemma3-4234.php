    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" name="register" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data (basic validation - improve this!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation - check for empty fields and email format
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    } elseif (strlen($password) < 8) { // Example: Password must be at least 8 characters
        $error_message = "Password must be at least 8 characters.";
    } else {
        // Prepare the SQL query to prevent SQL injection
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Construct the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($GLOBALS["conn"], $sql)) {
            $success_message = "Registration successful! You have been redirected.";
            // Redirect to a success page or login page
            header("Location: login.php"); //  Or redirect to your success page
            exit(); // Stop further script execution
        } else {
            $error_message = "Error: " . mysqli_error($GLOBALS["conn"]);
        }
    }
}
?>
