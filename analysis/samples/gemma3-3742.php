    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate the input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    }

    // Password validation (basic check - you should use a stronger hashing method in production)
    if (empty($password)) {
        $error_message = "Password cannot be empty.";
    }

    // Check if username is already registered (basic check - use prepared statements in production)
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?"); // Replace $conn with your database connection
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $error_message = "Username already exists.";
    }


    // If there are no errors, insert the new user into the database
    if (empty($error_message)) {
        // Hash the password (IMPORTANT - use a strong hashing function in production!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement for insertion
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

        // Bind the parameters
        $stmt->bind_param("ss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Successful registration
            echo "<p style='color: green;'>Registration successful! You have been redirected.</p>";
            // You can redirect to a success page here.  For example:
            // header("Location: success.php");
            // exit();
        } else {
            // Handle errors if the query fails
            echo "<p style='color: red;'>Error registering user.  Check the server logs for details.</p>";
        }
    }
}
?>
