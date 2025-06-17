
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
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

    // SQL query to check credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        //  This is a basic implementation. For security,
        //  you should consider storing user information
        //  in a more secure way, like a session cookie.

        $user_data = $result->fetch_assoc();
        $_SESSION['user_id'] = $user_data['id']; // Assuming you have an 'id' column
        $_SESSION['username'] = $user_data['username'];

        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example Usage (Demonstration -  This should be part of a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a protected page or display a success message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
    } else {
        // Display error message
        echo "<p>Invalid username or password.</p>";
    }
}

?>
