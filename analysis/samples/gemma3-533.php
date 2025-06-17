    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password != $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);

        // Prepare the insert statement
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed_password]);

        // Success message
        $success_message = "Registration successful!";

    }
}
?>
