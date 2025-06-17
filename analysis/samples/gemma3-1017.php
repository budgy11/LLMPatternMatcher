    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Replace register.php with your registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (important for security)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Hash the password (VERY IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query the database
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists
        if ($user) {
            // Verify the password
            if (password_verify($password, $user["password"])) {
                // User login successful
                session_start();
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                echo "Login successful!";
                // Redirect to a secure page or home page
                header("Location: secure_page.php"); // Replace with your secure page
                exit();
            } else {
                // Password mismatch
                $error = "Incorrect password.";
            }
        } else {
            // User does not exist
            $error = "User not found.";
        }
    }
}
?>
