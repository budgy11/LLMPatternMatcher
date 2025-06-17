        Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="' . htmlspecialchars($username) . '"'; ?> />
        <br/>
        Password: <input type="password" name="password" />
        <br/>
        <button type="submit">Login</button>
    </form>

    <br/>
    <a href="logout.php">Logout</a>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (IMPORTANT:  Always sanitize/validate user input!)
    $username = trim($username); // Remove leading/trailing whitespace
    $password = trim($password);


    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);  // Use prepared statements for security!
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found - set session variables
            $row = $result->fetch_assoc();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];

            // Redirect to a protected page or display a success message
            header("Location: protected_page.php"); // Or any other appropriate page
            exit();

        } else {
            // User not found
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Error executing query.";
    }
}

// Display the login form
?>
