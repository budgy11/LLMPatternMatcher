    Username: <input type="email" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

  <p>New user? <a href="register.php">Register here</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Query the database to check for the username
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error_message = "Invalid username or password.";
        } else {
            // If the login is successful, set a session variable
            session_start();
            $_SESSION["username"] = $username;
            $error_message = "Login successful!";

            // Redirect the user to a protected page or the homepage
            header("Location: welcome.php"); // Replace with your welcome page
            exit();
        }
    }
}
?>
