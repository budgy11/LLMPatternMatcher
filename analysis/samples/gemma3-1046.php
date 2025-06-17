    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - REPLACE THESE WITH YOUR ACTUAL VALUES
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

/**
 * User Login Function
 *
 * This function handles user login verification and session management.
 *
 * @param string $username The username entered by the user.
 * @param string $password The password entered by the user.
 * @return bool True on successful login, false otherwise.
 */
function loginUser($username, $password) {
    // 1. Validate Input (Important for security - prevent SQL injection)
    $username = trim($username); // Remove leading/trailing whitespace
    $password = trim($password);

    if (empty($username) || empty($password)) {
        return false; // Require both username and password
    }

    // 2. Database Query
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statements to prevent SQL injection (HIGHLY RECOMMENDED)
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // "s" indicates a string parameter

    if ($stmt->execute() === false) {
        die("Query failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    // 3. Password Verification
    if (password_verify($password, $user['password'])) {
        // 4. Session Management
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    } else {
        return false; // Password incorrect
    }
}

// Example Usage (Demonstration - NOT recommended for direct use in a web app)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        echo "Login successful! You are now logged in.";
        // Redirect to a protected page or display a welcome message.
    } else {
        echo "Invalid username or password.";
    }
}
?>
