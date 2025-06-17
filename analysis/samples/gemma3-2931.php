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

// Database Configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate input
    $username = trim($username);
    $email = trim($email);
    $password = trim($password);

    // Check if username and email are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if username already exists
        $stmt = $db->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $result = $stmt->execute();
        $user = $result->fetch_assoc();

        if ($user) {
            $error = "Username already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("ss", $username, $email, $hashed_password);
            $stmt->execute();

            // Redirect to a success page or display a success message
            echo "<p style='color: green;'>Registration successful!  Please check your email to verify your account.</p>";
            //  Optionally redirect:
            // header("Location: success.php");  // Assuming you have a success.php page
            exit();
        }
    }
}
?>
