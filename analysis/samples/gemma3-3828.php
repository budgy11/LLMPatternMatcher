    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Sanitize and validate the input
    $username = filter_var($username, FILTER_SANITIZE_STRING); // Clean the username
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize the email
    $password = filter_var($password, FILTER_SANITIZE_STRING); // Sanitize the password

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Password validation (simple example - you should use a stronger method)
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    }
    elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }


    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['db_host'], $sql); // Using global db_host

    if (mysqli_num_rows($result) > 0) {
        $username_error = "Username already exists.";
    }

    // Hash the password (Important for security - Never store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    if (empty($username_error) && empty($email_error) && empty($password_error)) {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($GLOBALS['db_host'], $sql)) {
            // Registration successful
            echo "Registration successful! You have been redirected.";
            // Optionally redirect to a success page or login page
        } else {
            // Error inserting into the database
            echo "Error: " . mysqli_error($GLOBALS['db_host']);
        }
    }
}
?>
