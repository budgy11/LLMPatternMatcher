        Username: <input type="text" name="username" required>
        Password: <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check the credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, create a session
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id']; // Store the user's ID in the session
        $_SESSION['username'] = $row['username'];
        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example Usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a secure page after successful login
        header("Location: secure_page.php");
        exit(); // Stop further execution
    } else {
        // Handle login failure (e.g., display an error message)
        $error = "Invalid username or password.";
        echo "<p style='color:red;'>" . $error . "</p>";
    }
}

?>
