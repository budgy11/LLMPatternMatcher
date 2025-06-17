    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle login
function login($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check for the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $user_data = $result->fetch_assoc();
        $_SESSION['user_id'] = $user_data['id']; // Assuming you have an 'id' column
        $_SESSION['username'] = $user_data['username'];
        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example Usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get username and password from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (VERY IMPORTANT - add more robust validation here!)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } else {
        $login_result = login($username, $password);
        if ($login_result) {
            echo "Login successful!  Redirecting...";
            header("Location: welcome.php"); // Redirect to a welcome page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}

?>
