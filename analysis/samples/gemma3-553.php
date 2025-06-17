    Username: <input type="text" name="username" placeholder="Enter your username" />
    <br />
    Password: <input type="password" name="password" placeholder="Enter your password" />
    <br />
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Crucial for security - See details below)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid Username (Must be a valid email address).";
    }  // Add more validation here (e.g., password complexity, minimum length, etc.)


    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query (Use prepared statements for security!)
    $sql = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username); // "s" indicates a string parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found - Verify password
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                // Password correct - Login successful
                echo "Login successful!";
                // Session management (Important for security - see example below)
                session_start();
                $_SESSION["username"] = $username;
                // Redirect to a protected page or display a welcome message.
                header("Location: /protected_page.php"); // Change to your desired URL
                exit();
            } else {
                // Password incorrect
                echo "Incorrect password.";
            }
        } else {
            // User not found
            echo "User not found.";
        }
    } else {
        // Error preparing statement
        echo "Error preparing statement.";
    }

    $stmt->close();
    $conn->close();
} else {
    // If the form hasn't been submitted, display the login form
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
    echo "Username: <input type='text' name='username'><br>";
    echo "Password: <input type='password' name='password'><br>";
    echo "<input type='submit' value='Login'>";
    echo "</form>";
}

?>
