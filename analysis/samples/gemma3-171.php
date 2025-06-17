    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve user data
    $sql = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "' AND password = '" . $conn->real_escape_string($password) . "'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, create a session
        $user_data = $result->fetch_assoc();
        session_start();
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['user_id'] = $user_data['id']; // Assuming you have an 'id' column

        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}


// Example Usage (Handling the login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a welcome page or the intended destination
        header("Location: welcome.php"); // Replace welcome.php with your welcome page
        exit();
    } else {
        // Handle login failure (e.g., display an error message)
        $error = "Invalid username or password.";
        // You might want to display this error on a specific page, e.g., login.php
    }
}

?>
