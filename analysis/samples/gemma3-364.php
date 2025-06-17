    Username: <input type="text" name="username" placeholder="Your Username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (important for security)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid Email Address.";
    } else {
        // Database query
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query (prevent SQL injection)
        $query = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, set session variables
                session_start();
                $user_data = $result->fetch_assoc();
                $_SESSION["user_id"] = $user_data["id"];
                $_SESSION["username"] = $user_data["username"];
                // You might also store other user data here, like roles or permissions

                // Redirect to a secure page or display a welcome message
                header("Location: welcome.php"); // Replace 'welcome.php' with your welcome page
                exit(); // Important to stop further execution
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Error executing query.";
        }
    }
}
?>
