        Username: <input type="text" name="username">
        Password: <input type="password" name="password">
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Form data from the login form
$username = $_POST['username'];
$password = $_POST['password'];

// Validate form data (very basic - improve for production)
if (empty($username) || empty($password)) {
    $error = 'Username and password cannot be empty.';
}

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Prepare and execute the query (using prepared statements - BEST PRACTICE)
$sql = "SELECT id, username, password FROM users WHERE username = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // 's' indicates a string parameter
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $error = 'Invalid username or password.';
} else {
    $row = $result->fetch_assoc();
    // Verify password (HASHED password from the database)
    if (password_verify($password, $row['password'])) {
        // Authentication successful!  Set session variables
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
        header("Location: /welcome.php"); // Redirect to a welcome page
        exit();
    } else {
        $error = 'Incorrect password.';
    }
}

// Handle errors
if ($error != "") {
    echo "<p style='color: red;'>$error</p>";
}

// Close the connection
$stmt->close();
$conn->close();

?>
