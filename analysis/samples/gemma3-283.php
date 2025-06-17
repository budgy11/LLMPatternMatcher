        Username: <input type="text" name="username" placeholder="Your Username"><br><br>
        Password: <input type="password" name="password" placeholder="Your Password"><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php
// Database credentials
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (still important!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password cannot be empty.";
    } else {
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ss", $username, $password);  // "ss" means two strings

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Close the statement
        $stmt->close();


        if ($result->num_rows == 0) {
            $error_message = "Invalid username or password.";
        } else {
            session_start();
            $_SESSION["username"] = $username;
            $error_message = "Login successful!";
            header("Location: welcome.php");
            exit();
        }
    }
}
?>
