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

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Validation functions (Implement more robust validation)
    if (empty($username) || empty($password) || empty($email)) {
        $errors[] = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    } elseif (preg_match('/^\w+$/', $username) == 0) {
        $errors[] = "Username must contain only alphanumeric characters.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    } elseif (preg_match('/^\w+$/', $password) == 0) {
        $errors[] = "Password must contain only alphanumeric characters.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = "Invalid email format.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Escape data for security (IMPORTANT)
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        $email = mysqli_real_escape_string($conn, $email);

        // Hash the password (IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // Registration successful
            echo "Registration successful! Please log in.";
        } else {
            // Registration failed
            echo "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>
