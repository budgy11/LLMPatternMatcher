
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (IMPORTANT: Sanitize and validate user input!)
    $username = filter_var($username, FILTER_SANITIZE_STRING); // Sanitize
    $password = filter_var($password, FILTER_SANITIZE_STRING); // Sanitize

    // Validate username is not empty
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Hash the password (Important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the query
        $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password); // "ss" indicates two string parameters
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found, set session variables
            session_start();
            $user = $result->fetch_assoc();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            echo "Login successful!  Redirecting...";
            // Redirect to a secure page or show a success message
            header("Location: secure_page.php"); // Example: Redirect to a secure page
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>
