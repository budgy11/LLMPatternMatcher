    Username: <input type="text" name="username" placeholder="your_email@example.com" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>  <!-- Link to registration page (create one) -->

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Get result set

        if ($result->num_rows > 0) {
            // User exists, return user data (you can customize this)
            $user_data = $result->fetch_assoc();
            return $user_data;
        } else {
            return false; // User not found
        }
    } else {
        return false; // Query error
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}


// Example usage (Handle login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (basic example - improve for production)
    if (empty($username) || empty($password)) {
        echo "Username and password fields cannot be empty.";
    } else {
        $loginResult = loginUser($username, $password);

        if ($loginResult) {
            // Successful login
            session_start();  // Start a session to store user data
            $_SESSION["username"] = $loginResult["username"]; // Store username in session
            $_SESSION["user_id"] = $loginResult["user_id"]; // Store user_id for more efficient database lookups
            echo "Login successful!  Redirecting...";
            // Redirect to a secure page or dashboard
            header("Location: /dashboard.php"); // Replace with your dashboard URL
            exit();

        } else {
            echo "Invalid username or password.";
        }
    }
}


?>
