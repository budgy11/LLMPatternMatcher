        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>

        <br><br>

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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Sanitize the input
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match("/^[a-zA-Z0-9._-]+$/", $username)) {
        $error = "Username must contain only letters, numbers, and periods/underscores/hyphens.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";

        if ($conn->query($sql) === TRUE) {
            $success = "Registration successful!  You have been logged in.";
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>
