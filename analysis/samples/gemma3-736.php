        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>

        <button type="submit">Login</button>
    </form>

    <br><br>
    <a href="register.php">Not a user? Register here.</a>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (important for security)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password must be filled.";
    } else {
        //  **IMPORTANT SECURITY NOTE:**  NEVER store passwords directly in your code or database.
        //  Always hash the password before storing it in the database.
        //  This example uses a simple comparison for demonstration purposes ONLY.

        // Attempt to connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to check for the username
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error_message = "Invalid username or password.";
        } else {
            // If the query executes successfully, check the password
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) { // Secure password comparison using password_verify
                // Login successful!  Session management is crucial here.
                session_start();
                $_SESSION["username"] = $username;  // Store the username in the session
                $error_message = "Login successful!";
                // Redirect to a secure page or display a welcome message
                header("Location: welcome.php"); // Assuming you have a welcome.php page
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        }
    }
}
?>
