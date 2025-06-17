    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user data from the form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } else if ($password != $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert the new user into the database
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "New user created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
