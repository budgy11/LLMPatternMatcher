    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute() === TRUE) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Successful login
            session_start(); // Start a session to store user information
            $_SESSION['username'] = $row['username'];  // Store username in session
            $_SESSION['user_id'] = $row['id']; //  Optionally, store the user ID.
            return true; // Indicate successful login
        } else {
            return false; // Indicate login failed (no user found)
        }
    } else {
        // Error executing query
        return false;
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();
}

// Example usage:  (This is just for testing - you wouldn't usually include this in a production environment)
// $username = "testuser";
// $password = "password123";

// if (loginUser($username, $password)) {
//     echo "Login successful!";
//     // Redirect to a protected page or do something else
// } else {
//     echo "Login failed. Please check your credentials.";
// }

?>
