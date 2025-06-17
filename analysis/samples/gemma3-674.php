
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h1>Login</h1>

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

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Crucial for security)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Hash the password (Important for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query the database to check credentials
        $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = mysqli_query($db_host, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verify the password
            if (password_verify($password, $row["password"])) {
                // Successful login
                // Set a session variable to store the user's ID
                session_start();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $username; // Optionally store username
                
                // Redirect the user to a protected page
                header("Location: protected_page.php"); // Replace with your protected page
                exit(); // Important to stop further script execution
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "Username not found.";
        }
    }
}
?>
