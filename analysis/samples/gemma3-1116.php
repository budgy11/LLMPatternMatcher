    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple (and potentially insecure) 
 * username/password comparison.
 *
 * **WARNING:** This is a basic example for demonstration purposes only.
 *  For production environments, use a secure authentication system 
 *  like hashing passwords with bcrypt or Argon2, and consider using 
 *  a library or framework for managing user sessions and security.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password)
{
    // *** IMPORTANT SECURITY WARNING:  Do NOT store passwords in plain text. ***
    // This is just for demonstration purposes.  A real system would hash the password.

    // ** Replace with your database connection code**
    $db_host = 'localhost';
    $db_name = 'your_database_name';
    $db_user = 'your_database_user';
    $db_password = 'your_database_password';

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input -  This is extremely important to prevent SQL injection!
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // SQL Query -  Be very careful with this.  Validate inputs before constructing queries.
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Compare passwords -  Again, *never* store passwords in plain text.
        if (password_verify($password, $row['password'])) {
            // Login successful!

            // ***  Set Session (Replace with your preferred session management method) ***
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            return true;
        } else {
            // Password mismatch
            return false;
        }
    } else {
        // No user found
        return false;
    }

    // Close connection
    $conn->close();
}


// --- Example Usage ---
// Assuming you have a form that captures the username and password:

// If the login is successful:
// echo "Login successful!  Welcome, " . $_SESSION['username'] . "!";

// If the login fails:
// echo "Login failed. Please check your credentials.";


?>
