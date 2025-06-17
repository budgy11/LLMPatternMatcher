    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) { // Example: Validate email format
        $error = "Invalid username (please enter a valid email)";
    } else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to check username and password
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // User found, set session variables
            session_start();
            $_SESSION["username"] = $username;
            echo "Login successful!  Redirecting...";
            // Redirect to a protected page or the homepage
            header("Location: protected_page.php"); // Replace with your protected page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
