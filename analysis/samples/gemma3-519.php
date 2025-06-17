    Username: <input type="email" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual details)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check credentials
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //IMPORTANT: Use prepared statements for security!

        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // User found, set session variables
            session_start();
            $_SESSION["username"] = $username; //Store username in session
            $_SESSION["user_id"] = $result->fetch_assoc()["id"]; //Assuming you have an 'id' column in your users table.
            $error_message = "";  //Clear any previous errors
            header("Location: welcome.php"); // Redirect to a welcome page
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>
