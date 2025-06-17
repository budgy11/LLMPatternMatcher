        <br><br>
        <button type="submit">Login</button>
    </form>

    <br><br>
    <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate the input (important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } elseif (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password'")) {
        // User exists, so you can redirect them to the main page or a secure area.
        session_start();
        $_SESSION["username"] = $username; // Store the username in the session
        header("Location: welcome.php"); // Redirect to a welcome page
        exit(); // Stop further execution
    } else {
        $error = "Invalid username or password.";
    }
}
?>
