    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to a registration page -->

</body>
</html>


<?php
session_start();

/**
 * User Login Function
 *
 * Handles user login and session management.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True on successful login, false otherwise.
 */
function loginUser(string $username, string $password) {
    // *** IMPORTANT: Replace this with your actual database connection and credentials ***
    $dbHost = "localhost";
    $dbUsername = "your_db_username";
    $dbPassword = "your_db_password";
    $dbName = "your_db_name";

    // Establish database connection
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check username and password
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password (using password_hash if you're storing hashed passwords)
        if (password_verify($password, $row['password'])) { // Using password_verify
            // Login successful
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id']; // Store the user ID
            return true;
        } else {
            // Incorrect password
            return false;
        }
    } else {
        // User not found
        return false;
    }

    $conn->close();
}


/**
 * Logout Function
 *
 * Clears the session and redirects to a login page or the homepage.
 */
function logoutUser() {
    session_destroy();
    // Redirect to login page or homepage
    header("Location: login.php"); // Or your desired destination
    exit();
}

// Example Usage (Illustrative - you'll need to integrate this into your form submission)
//  Assume you've handled form submission and obtained $username and $password
//  $username = $_POST['username'];
//  $password = $_POST['password'];

// if (loginUser($username, $password)) {
//     // Successful login - redirect to homepage or protected area
//     header("Location: homepage.php");
//     exit();
// } else {
//     // Login failed - display error message
//     echo "<p style='color:red;'>Invalid username or password.</p>";
// }

?>
