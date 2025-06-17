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

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (basic validation - expand for production)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if username, email, and password are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    }
    // Check if username is already taken
    if (!empty($username)) {
        $sql = "SELECT username FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $error = "Username is already taken.";
        }
    }

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }

    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // If no errors, insert the user into the database
    if (empty($error)) {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            // Registration successful
            $success = "Registration successful.  You have been logged in.";
        } else {
            // Something went wrong with the database query
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Connect to the database
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
