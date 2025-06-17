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

// Database credentials (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data - VERY IMPORTANT to prevent security vulnerabilities
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Validate input data
    $username = trim($username); // Remove whitespace
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING); // Re-sanitize for confirmation
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);


    // Check if required fields are filled
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (preg_match('/@/', $email)) {
        $error = "Invalid email format.";
    }
    else {
        // Hash the password (VERY IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

        // Prepare the statement (using PDO - more secure and efficient)
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $hashed_password, $email]);
            $success = "Registration successful.  Please check your email to verify your account.";

        } catch (PDOException $e) {
            $error = "An error occurred: " . $e->getMessage();
        }
    }
}
?>
