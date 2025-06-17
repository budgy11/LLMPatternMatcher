    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form input (Crucial for security!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username (basic example - customize as needed)
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters long.";
    }

    // Validate email (basic example - use a proper validation library for robust validation)
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate password (basic example - require minimum length and special characters)
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Check if there are any errors
    if (!empty($username_error)) {
        $errors = ["username" => $username_error];
    } elseif (!empty($email_error)) {
        $errors = ["email" => $email_error];
    } elseif (!empty($password_error)) {
        $errors = ["password" => $password_error];
    } else {
        // No errors, proceed with registration
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $hashed_password); //Bind parameters
            $stmt->execute();
            $stmt->close();
        } else {
            die("Error preparing statement: " . print_r($conn->error, true));
        }


        // Success message
        $success_message = "Registration successful! Please log in.";

        // Redirect to login page or homepage
        header("Location: login.php"); // Assuming you have a login.php page
        exit();
    }
}

?>
