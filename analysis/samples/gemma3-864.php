    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$db_host = "localhost";
$db_name = "your_database_name";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $user_username = $_POST["username"];
    $user_password = $_POST["password"];

    // Validate input (important for security)
    if (empty($user_username) || empty($user_password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Hash the password (essential for security)
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // Query the database
        $conn = new mysqli($db_host, $username, $password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL Query
        $sql = "SELECT id, username, password FROM users WHERE username = '$user_username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Compare the hashed password with the submitted password
            if (password_verify($user_password, $row["password"])) {
                // Login successful
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                echo "Login successful!";
                header("Location: welcome.php"); // Redirect to a welcome page
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "Username not found.";
        }
    }
}
?>
