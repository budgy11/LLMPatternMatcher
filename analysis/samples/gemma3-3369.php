    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validation - Important!  Add more validation as needed.
    if (empty($username) || empty($password) || empty($email)) {
        $error_message = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error_message = "Username must be at least 3 characters long.";
    } elseif (preg_match('/@/', $email)) {
        $error_message = "Invalid email format.";
    } else {
        // Password complexity (example - you should use stronger hashing)
        if (strlen($password) < 8) {
            $error_message = "Password must be at least 8 characters long.";
        }
         // Password complexity (example - you should use stronger hashing)
        // You should never store passwords in plain text.  Use password_hash()
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email, $password); // Use placeholders for security
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows == 1) {
            // Success!
            echo "Registration successful.  Please log in.";
        } else {
            // Error - this is rare but possible, e.g., duplicate username
            echo "Registration failed.  A user with this username/email already exists.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
