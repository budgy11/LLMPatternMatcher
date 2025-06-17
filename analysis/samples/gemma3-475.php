
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Form data from the login form
$username = $_POST['username'];
$password = $_POST['password'];

// Validate input (important to prevent common vulnerabilities)
if (empty($username) || empty($password)) {
    // Handle empty fields (e.g., display an error message)
    echo "<p>Username and password fields cannot be empty.</p>";
    exit; // Stop further execution
}

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$stmt = $conn->prepare($sql);

// Check if the prepared statement is ready
if (!$stmt->prepare($sql)) {
    echo "Error preparing statement";
    exit;
}

// Bind the password (using password_hash)
$stmt->bind_param("s", $password);

// Execute the query
$result = $stmt->execute();

// Check if the query was successful
if ($result) {
    // Get the result row
    $row = $stmt->fetch_assoc();

    // Check if a user was found
    if ($row['id']) {
        // Verify the password (using password_verify)
        if (password_verify($password, $row['password'])) {
            // User found and password verified!
            // Set session variables to track the user
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            // Redirect the user to a secure page (e.g., the dashboard)
            header("Location: dashboard.php"); // Assuming you have a dashboard.php page
            exit;
        } else {
            // Password mismatch
            echo "<p>Incorrect password.</p>";
        }
    } else {
        // User not found
        echo "<p>User not found.</p>";
    }
}

// Close the connection
$conn->close();

?>
