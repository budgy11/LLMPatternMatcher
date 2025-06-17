    Username: <input type="text" name="username" placeholder="Your Username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php
session_start();

// Database credentials - **IMPORTANT:  Never store these directly in your code.**
// Use environment variables or a configuration file.
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

/**
 * Logs in a user based on their credentials.
 *
 * @param string $username The username to log in.
 * @param string $password The password for the user.
 * @return bool True if login was successful, false otherwise.
 */
function loginUser(string $username, string $password)
{
    global $db_host, $db_name, $db_user, $db_password;

    // Attempt to connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            // Set session variables for the logged-in user
            $_SESSION["user_id"] = $user_data["id"];
            $_SESSION["username"] = $user_data["username"];

            return true;
        } else {
            return false; // User not found
        }
    } else {
        return false; // SQL error
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}


/**
 * Handles the login form submission.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Login successful, redirect to a protected page or display a success message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
    } else {
        // Login failed, display an error message
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}

// Example Login Form
?>
