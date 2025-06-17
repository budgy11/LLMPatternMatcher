    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Login successful
            $_SESSION['username'] = $username;  // Store username in session
            return true;
        } else {
            // Password incorrect
            return false;
        }
    } else {
        // User not found
        return false;
    }

    // Close connection (important!)
    $conn->close();
}


// Example Usage:  This will be triggered by a form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a secure page or display a welcome message
        header("Location: /welcome.php"); // Replace /welcome.php with your welcome page
        exit();
    } else {
        // Login failed - display an error message
        $error = "Invalid username or password.";
        echo "<p style='color:red;'>$error</p>";
    }
}

?>
