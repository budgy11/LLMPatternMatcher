    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = mysqli_prepare($db_connection, $sql);
mysqli_stmt_bind_param($stmt, "s", $username); // "s" indicates a string
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// ... rest of the login logic using $result ...

mysqli_stmt_close($stmt);


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password must be filled.";
    } else {
        // Database connection
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query - Use prepared statements for security!
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // Successful login
                session_start(); // Start the session
                $_SESSION["username"] = $username; // Store the username in the session
                $user_data = $result->fetch_assoc();
                $_SESSION["user_id"] = $user_data["id"]; //  Store the user ID as well (recommended)
                header("Location: welcome.php"); // Redirect to a welcome page
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Error preparing SQL statement.";
        }
    }
}
?>
