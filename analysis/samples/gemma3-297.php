        Username: <input type="email" name="username" required>
        Password: <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>

</body>
</html>


<?php
// ... (Database connection - same as login.php)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Basic - expand this!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password must be filled.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid username.  Please enter a valid email address.";
    }  elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    }

    if (empty($error_message)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password); // "ss" for string parameters
        $stmt->execute();

        if ($stmt->error) {
            die("Error inserting user: " . $stmt->error);
        }

        $success_message = "Registration successful.  Check your email to verify your account.";
    }
}
?>
