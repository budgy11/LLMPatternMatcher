    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important to prevent SQL injection and other issues)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query (using prepared statements -  VERY IMPORTANT!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

        // Bind the values to the query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

        // Execute the query
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found, set session variables
                session_start(); // Start a session if not already started
                $_SESSION["username"] = $username;
                $result->fetch_assoc(); // Fetch the user data (for consistency)

                // Redirect to a protected page or display a success message
                header("Location: /protected_page.php"); // Replace with your protected page URL
                exit();

            } else {
                $error = "Invalid username or password.";
            }

        } else {
            $error = "Query execution failed: " . $conn->error;
        }
    }
}
?>
