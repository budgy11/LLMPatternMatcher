
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation rules (customize as needed)
    $username_regex = "/^[a-zA-Z0-9_]+$/"; // Alphanumeric and underscore only
    $email_regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if (empty($username) || empty($email) || empty($password)) {
        $errors = ["Username", "Email", "Password"] . " cannot be empty.";
    } elseif (!preg_match($username_regex, $username)) {
        $errors = "Username must contain only letters, numbers, and underscores.";
    } elseif (!preg_match($email_regex, $email)) {
        $errors = "Invalid email address.";
    } elseif (strlen($password) < 8) {
        $errors = "Password must be at least 8 characters long.";
    } else {
        // Password hashing (very important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email, $hashed_password); // "ss" indicates two strings
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email to verify.";
            // You might redirect here or display a success message
        } else {
            // Registration failed
            echo "Registration failed: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
