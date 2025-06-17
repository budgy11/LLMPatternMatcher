    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Form handling (POST method)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Basic - should be more robust in a real application)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        // Authenticate against the database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL Query (Using prepared statements for security!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, set session variables
                session_start();
                $_SESSION["username"] = $username;
                $stmt->close();
                $conn->close();
                header("Location: welcome.php"); // Redirect to welcome page
                exit();
            } else {
                $error_message = "Invalid username or password.";
                $stmt->close();
                $conn->close();
            }
        } else {
            $error_message = "Error preparing statement.";
            $conn->close();
        }
    }
}
?>
