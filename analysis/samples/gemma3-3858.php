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
// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input (same validation as before)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Sanitize input (same sanitization as before)
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    }
    // Password validation - requires at least 8 characters
    if (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    }

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = ?"; // Use ? placeholder
    $result = mysqli_query($db_host, $sql, [$username]); // Pass the username as an array
    if (mysqli_num_rows($result) > 0) {
        $error_message = "Username already exists.";
    }

    // If no errors, insert into the database
    if (empty($error_message)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query for insertion using prepared statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($db_host, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password); // "sss" indicates three string parameters
            mysqli_stmt_execute($stmt);
            //success
        } else {
            echo "Error preparing statement."; // Handle the error
        }

        // Redirect to a success page
        header("Location: success.php"); // Or your desired success page
        exit();
    }
}
?>
