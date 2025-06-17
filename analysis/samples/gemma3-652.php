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

// Database configuration (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password must be filled.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the query
        $sql = "SELECT username, password FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        } else {
            $row = $result->fetch_assoc();
            // Verify password (important!)
            if (password_verify($password, $row["password"])) {
                // Successful login - store the username in a session
                session_start();
                $_SESSION["username"] = $username;
                echo "Login successful!";
                // Redirect to a secure page (e.g., dashboard)
                header("Location: dashboard.php");
                die();
            } else {
                $error = "Invalid username or password.";
            }
        }
    }
}
?>
