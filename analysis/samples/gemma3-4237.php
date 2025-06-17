    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Form handling (Registration Form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Form Data
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Validation Rules (Basic - You should implement more robust validation)
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    // Email format validation (simple check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS["host"], $sql);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Username already exists.";
    }

    // If no errors, insert the data into the database
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);  // Securely hash the password

        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password_hash', '$email')";

        if (mysqli_query($GLOBALS["host"], $sql)) {
            // Registration successful
            echo "<p style='color: green;'>Registration successful!  Please check your email to confirm your account.</p>";
        } else {
            // Error during insertion
            echo "<p style='color: red;'>Error: " . mysqli_error($GLOBALS["host"]) . "</p>";
        }
    }
}

?>
