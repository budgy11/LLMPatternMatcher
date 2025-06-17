    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Replace register.php with your registration page -->

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Login successful
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                return true;
            } else {
                // Incorrect password
                return false;
            }
        } else {
            // User not found
            return false;
        }
    } else {
        // Error preparing the statement
        return false;
    }

    // Close the connection
    $conn->close();
}


// Example Usage:
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    if (loginUser($username, $password)) {
        // Login successful - Redirect to a secure page or display a welcome message
        header("Location: secure_page.php"); // Replace with your secure page
        exit();
    } else {
        // Login failed - Display an error message
        echo "<p style='color:red;'>Invalid username or password.</p>";
    }
}

?>
