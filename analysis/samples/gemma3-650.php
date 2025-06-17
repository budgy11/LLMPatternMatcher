
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle user login
function loginUser($username, $password) {
    // SQL query to retrieve user information
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return false; // User not found
    }

    $user_data = $result->fetch_assoc();
    $hashed_password = $user_data['password'];

    // Verify the password using password_verify
    if (password_verify($password, $hashed_password)) {
        // Password matches, authenticate the user
        // You can store user session data here (e.g., user ID)
        // For this example, we'll just return true.
        return true;
    } else {
        return false; // Incorrect password
    }
}

// Example Usage (Simulating a login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // User is logged in
        echo "Login successful!";
        // Start a session to track the logged-in user
        session_start();
        $_SESSION["user_id"] = $user_data['id']; // Store the user's ID in the session
        // You can redirect the user to a secure page here.
    } else {
        echo "Invalid username or password.";
    }
}

?>
