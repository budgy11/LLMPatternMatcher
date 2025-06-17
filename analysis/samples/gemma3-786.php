    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login functionality, including:
 * - Checking username and password against a database.
 * - Setting session variables for successful login.
 * - Returning true for successful login, false for failure.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) : bool
{
    // Database connection details - REPLACE WITH YOUR ACTUAL VALUES
    $dbHost = 'localhost';
    $dbName = 'your_database_name';
    $dbUser = 'your_database_user';
    $dbPass = 'your_database_password';


    // Connect to the database
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    // Check connection
    if ($conn->connect_error) {
        // Handle connection error - Log the error or display a user-friendly message
        error_log("Failed to connect to database: " . $conn->connect_error);
        return false;
    }


    // SQL Query - Adjust this query based on your database schema
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";

    // Prepare the statement (using prepared statements is crucial for security)
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Handle statement preparation error - Log the error or display a user-friendly message
        error_log("Error preparing statement: " . $conn->error);
        return false;
    }

    // Bind parameters
    $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters

    // Execute the statement
    if (!$stmt->execute()) {
        // Handle execution error - Log the error or display a user-friendly message
        error_log("Error executing statement: " . $stmt->error);
        return false;
    }


    // Get the result
    $result = $stmt->get_result();

    if ($result === false) {
       error_log("Error getting result: " . $stmt->error);
       return false;
    }

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // User found - Set session variables
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // You might want to add other user data to the session, like roles, etc.

        // Close the statement and connection
        $stmt->close();
        $conn->close();
        return true;
    } else {
        // User not found
        $stmt->close();
        $conn->close();
        return false;
    }
}



// Example Usage (Illustrative - Replace with your form handling)
// You'll typically get username and password from a form.

// $username = $_POST['username'];
// $password = $_POST['password'];

// if (loginUser($username, $password)) {
//     echo "Login successful!  Redirecting...";
//     header("Location: /dashboard.php"); // Replace with your dashboard URL
//     exit;
// } else {
//     echo "Invalid username or password.";
// }

?>
