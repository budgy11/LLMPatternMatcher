        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation checks
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }


    // If there are no errors, register the user
    if (empty($errors)) {

        // Escape the input to prevent SQL injection
        $username = mysqli_real_escape_string($connection, $username);
        $email = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);

        // Hash the password (IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        $result = mysqli_query($connection, $query);

        if ($result) {
            echo "<p style='color: green;'>Registration successful! You have been redirected.</p>";
            // Redirect to a welcome page or login page
            header("Location: welcome.php"); // Replace welcome.php with your desired redirect
            exit();
        } else {
            echo "<p style='color: red;'>Error registering user. " . mysqli_error($connection) . "</p>";
        }
    }
}
?>
