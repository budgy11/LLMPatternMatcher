    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
// Similar database connection (replace with your details)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password != $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);

            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);

            $error_message = "Registration successful.  Please check your email for verification instructions."; // Good practice to send verification email here

        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
} else {
    $error_message = "";
}
?>
