        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <br><br>
    <a href="register.php">Not registered? Register here.</a>

</body>
</html>


<?php
// Database connection details - Replace with your actual details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Username and Password must be filled.";
    } else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "New user created successfully!";
            // Redirect to the login page
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
