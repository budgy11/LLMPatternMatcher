    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Form handling (registration form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validation checks
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (empty($confirm_password)) {
        $errors[] = "Confirm Password cannot be empty.";
    }
    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            // Registration successful
            echo "Registration successful!  Please <a href='login.php'>login</a>.";
        } else {
            // Registration failed
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}

?>
