    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            // Successful login
            session_start();
            // Store user data in session
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            return true;
        } else {
            // User not found
            return false;
        }
    } else {
        // Error executing the query
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage (within a form or other input method)
// $username = $_POST['username'];
// $password = $_POST['password'];
// if (loginUser($username, $password)) {
//     // Login successful, redirect to a secure page
//     header("Location: secure_page.php");
//     exit();
// } else {
//     // Login failed, display an error message
//     echo "Invalid username or password.";
// }

?>
