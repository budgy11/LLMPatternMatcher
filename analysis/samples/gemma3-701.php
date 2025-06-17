    Username: <input type="text" name="username" placeholder="Enter your username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here!</a>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (basic example - enhance for security in a real application)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (filter_var($username, FILTER_VALIDATE_EMAIL) === false) {
        $error = "Invalid email format.";
    } else {
        // Attempt to connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to check username and password
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // User found, set session variables
            session_start();
            $_SESSION["username"] = $username;  // Store the username in the session
            $result->fetch_assoc(); // Fetch the user data for better security
            echo "Login successful!";
            // Redirect to a secure page or display a welcome message
            header("Location: welcome.php"); // Replace welcome.php with your desired page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
