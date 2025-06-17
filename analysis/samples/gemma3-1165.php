        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to check login credentials
function login($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $query = "SELECT id, username, password FROM users WHERE username = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // "s" indicates a string parameter

    // Execute the query
    if ($stmt->execute() === TRUE) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Successful Login
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                return true;
            } else {
                // Incorrect password
                return false;
            }
        } else {
            // No user found
            return false;
        }
    } else {
        // Query error
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example Usage (Form handling - this is simplified)

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (VERY IMPORTANT for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Call the login function
        $login_result = login($username, $password);

        if ($login_result) {
            // Redirect to a protected page
            header("Location: protected_page.php"); // Replace with your protected page URL
            exit();
        } else {
            $error = "Incorrect username or password.";
        }
    }
}
?>
