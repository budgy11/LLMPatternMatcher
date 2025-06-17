        <label for="username">Email:</label>
        <input type="email" id="username" name="username" placeholder="Your Email">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Your Password">
        <br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (IMPORTANT - Always validate user input!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the password (using password_hash - preferred method)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query the database to check credentials
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);

        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the hashed password matches
                if (password_verify($password, $user["password"])) {
                    // Login successful
                    session_start();
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    header("Location: welcome.php"); // Redirect to a welcome page
                    exit(); // Important: Stop further execution
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "Username not found.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
