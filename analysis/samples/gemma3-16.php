    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
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

    // SQL query to check username and password
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, set session variables
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id']; // Assuming you have a user ID field
        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example usage (handling the login form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a protected page or display a success message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
    } else {
        // Display an error message
        $error = "Invalid username or password.";
    }
}

?>
