    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
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
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check the credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error_message = "Invalid username or password.";
        } else {
            // Successful login - Redirect to the welcome page or a protected area
            session_start();
            $_SESSION['username'] = $username;  // Store the username in the session
            header("Location: welcome.php"); // Redirect to a welcome page
            exit(); // Important to stop further execution
        }
    }
}
?>
