        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <br />
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php
// Database credentials (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (Basic validation - improve this for production)
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password cannot be empty.";
    } else {
        // Database connection using PDO
        try {
            $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

            // SQL query to check the credentials using prepared statement
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Successful login - Redirect to the welcome page or a protected area
                session_start();
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }

        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}
?>
