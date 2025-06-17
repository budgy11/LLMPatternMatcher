    Username: <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
    <br><br>
    Email: <input type="email" name="email">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    Confirm Password: <input type="password" name="confirm_password">
    <br><br>
    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    }
    // Validate username (basic example, adjust as needed)
    if (empty($username)) {
        $error_message = "Username cannot be empty.";
    }

    // Validate password (basic example -  consider stronger requirements)
    if (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    }

    // Hash the password (IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            $success_message = "Registration successful!  Please check your email to verify your account.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    } else {
        $error_message = "Error preparing statement.";
    }
    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
