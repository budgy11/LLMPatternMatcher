    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and Password cannot be empty!";
    }
    elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    }
    else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query (Prevent SQL Injection!)
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters (Crucial for security!)
            $stmt->bind_param("ss", $username, $password);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if any rows were returned
            if ($result->num_rows > 0) {
                // User found!  Start a session.
                session_start();
                $_SESSION["username"] = $username; // Store username in session
                $result->fetch_assoc(); // Get user details (optional, for further processing)
                $error = ""; // Clear any previous errors
                echo "Login successful!  You are now logged in.";
                header("Location: /welcome.php"); // Redirect to a welcome page
                exit();

            } else {
                $error = "Invalid username or password.";
            }

        } else {
            $error = "Error preparing statement.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
