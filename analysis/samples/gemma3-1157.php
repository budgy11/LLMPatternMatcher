
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate the input (important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password must be filled.";
    } else {
        // Query the database
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query - **IMPORTANT:  Use parameterized queries for security!**
        $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param("ss", $username, $password);

            // Execute the statement
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found
                $row = $result->fetch_assoc();
                $user_id = $row["id"];
                $username = $row["username"];
                // Set session variables
                session_start();
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;
                echo "Login successful!";
                // Redirect to a protected page (e.g., dashboard)
                header("Location: dashboard.php"); // Replace with your protected page
                exit(); // Important to stop further execution
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Error preparing the statement.";
        }
    }
}
?>
