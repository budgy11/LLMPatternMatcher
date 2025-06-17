    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$login_username = $_POST["username"];
$login_password = $_POST["password"];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query
$sql = "SELECT username, password FROM users WHERE username = '$login_username'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // User not found
    echo "Invalid username or password.";
} else {
    // User found, check password
    $user_data = $result->fetch_assoc();
    if (password_verify($login_password, $user_data["password"])) {
        // Password matches, successful login
        // Set a session variable to store the user's username
        session_start();
        $_SESSION["username"] = $login_username;
        echo "Login successful!  Redirecting...";

        // Redirect to a protected page or your main application page
        header("Location: /protected_page.php"); //  Replace with your protected page
        exit();
    } else {
        // Password does not match
        echo "Invalid username or password.";
    }
}

$conn->close();

?>
