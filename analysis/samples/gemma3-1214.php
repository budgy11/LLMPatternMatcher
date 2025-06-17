    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database connection details - **IMPORTANT: Replace with your actual details**
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (VERY IMPORTANT - prevent SQL injection)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid Email format.";
    } else {
        // Database query (using prepared statements for security)
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // "s" indicates a string parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify password (using password_hash for secure comparison)
            if (password_verify($password, $row["password"])) {
                // Login successful - set session variables
                session_start();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $username;
                echo "Login successful! <a href='dashboard.php'>Go to Dashboard</a>"; // Redirect after successful login
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Incorrect username.";
        }
    }
}
?>
