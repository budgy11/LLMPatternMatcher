        Password: <input type="password" name="password" value="<?php if (isset($password)) echo $password; ?>"><br><br>
        <input type="submit" value="Login">
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, set session variables
                session_start();
                $user_data = $result->fetch_assoc();
                $_SESSION["user_id"] = $user_data["id"];
                $_SESSION["username"] = $user_data["username"];
                // You could also store other user data here if needed
                echo "Login successful! <a href='welcome.php'>Welcome, " . $_SESSION["username"] . "</a>";
                $stmt->close();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Error preparing statement.";
        }
    }
}
?>
