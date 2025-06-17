    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate the input (basic validation - enhance as needed)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } elseif (strlen($username) < 3) {
        $error_message = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 5) {
        $error_message = "Password must be at least 5 characters long.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check the username and password
        $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $row["password"])) {
                // Login successful
                // Set session variables to store the user's ID
                session_start();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                echo "Login successful!";
                // Redirect the user to a welcome page or other appropriate location
                header("Location: welcome.php"); // Replace welcome.php with your welcome page
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "Username not found.";
        }
    }
}
?>
