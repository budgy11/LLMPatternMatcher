    Password: <input type="password" name="login_password" value="<?php if(isset($_POST['login_password'])) echo $_POST['login_password']; ?>">
    <button type="submit" name="login_button">Login</button>
</form>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve user information
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, create a session
        session_start();

        $user_data = $result->fetch_assoc();
        $_SESSION['user_id'] = $user_data['id']; // Store user ID in session
        $_SESSION['username'] = $user_data['username'];

        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example usage:  Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a protected page
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}

?>
