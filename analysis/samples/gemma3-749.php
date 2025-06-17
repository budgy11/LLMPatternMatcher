    Username: <input type="text" name="username" placeholder="Username"><br><br>
    Password: <input type="password" name="password" placeholder="Password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; 
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ss", $username, $password);

        // Execute the query
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, fetch the data
                $user = $result->fetch_assoc();

                // Session handling
                session_start();  // Start the session

                // Store user data in session (optional, but recommended)
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to a protected page (e.g., dashboard)
                header("Location: dashboard.php");
                exit(); // Important: Exit after redirecting

            } else {
                // User not found or incorrect password
                echo "Invalid username or password.";
            }
        } else {
            echo "Query execution failed: " . $conn->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

// Example usage (assuming this is called from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    loginUser($username, $password);
}

?>
