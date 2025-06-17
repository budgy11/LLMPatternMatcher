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

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation rules (Example - Adjust to your requirements)
    $username_regex = "/^[a-zA-Z0-9_]+$/"; // Allow only letters, numbers, and underscore
    $email_regex = "/^([a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/";

    if (empty($username) || empty($email) || empty($password)) {
        $errors = ["Username", "Email", "Password"] + (array)$errors ?? []; // Combine errors if array is null
    } elseif (!preg_match($username_regex, $username)) {
        $errors[] = "Username must only contain letters, numbers, and underscores.";
    } elseif (!preg_match($email_regex, $email)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password (Important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ss", $username, $hashed_password, $username); // username is bound twice - one for the username and one for the email

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! You have been redirected.";
            // Redirect to a success page or log the user in
            header("Location: success.php"); // Example: Redirect to a success page
            exit();
        } else {
            // Query failed
            echo "Query failed: " . $conn->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
