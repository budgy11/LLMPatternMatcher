    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];


// Input Validation (Crucial for security!)
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors = "All fields are required.";
} elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters.";
} elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors = "Invalid email format.";
} elseif ($password != $confirm_password) {
    $errors = "Passwords must match.";
} else {
    // Password Hashing (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL Query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if ($conn = mysqli_connect($host, $username, $password, $database)) {
        mysqli_query($conn, $sql);
        $success = "Registration successful. Please log in.";
        mysqli_close($conn);
    } else {
        $errors = "Connection failed.";
    }
}


// Display Errors or Success Message
if (isset($errors)) {
    echo "<div class='error'>$errors</div>";
} elseif (isset($success)) {
    echo "<div class='success'>$success</div>";
}


?>
