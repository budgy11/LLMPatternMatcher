    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get login details from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if the username exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row["password"])) {
            // If passwords match, set session variables
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $row["id"]; // Store the user ID as well for more robust security
            echo "Login successful! <a href=''>Continue to Dashboard</a>";
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    $conn->close();
}
?>
