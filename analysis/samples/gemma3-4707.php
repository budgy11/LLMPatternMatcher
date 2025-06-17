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

// Assuming you have a database connection established (e.g., via mysqli)
// This is a basic example, replace with your database connection details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (important for security!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation (add more robust validation as needed)
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Hash the password (VERY IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // Registration successful
            $success_message = "Registration successful! Please log in.";
        } else {
            // Registration failed
            $error_message = "Error registering. Please try again.";
            error_log(mysqli_error($conn)); // Log the error for debugging
        }
    }
}
?>
