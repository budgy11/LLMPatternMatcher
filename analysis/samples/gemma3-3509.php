        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate the input data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation -  Add more robust validation here!
    if (empty($username) || empty($email) || empty($password)) {
        $errors = "All fields are required.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $errors = "Password must be at least 6 characters long.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {

        // Hash the password - VERY IMPORTANT for security!
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        $result = mysqli_query($GLOBALS['db_host'], $sql); // Use mysqli_query for modern PHP

        if ($result) {
            // Registration successful - Redirect to a welcome page or login page
            header("Location: welcome.php"); // Replace 'welcome.php' with your success page
            exit();
        } else {
            $errors = "Error inserting data into the database. Please try again.";
        }
    }
}
?>
