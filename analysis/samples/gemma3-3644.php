    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login here</a></p> <!-- Replace "login.php" with your login page -->

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation - basic checks (more robust validation is recommended in a production environment)
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error_message = "Invalid email address.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        $result = mysqli_query($GLOBALS["db_host"], $sql);

        // Check if the query was successful
        if ($result) {
            $success_message = "Registration successful! Please check your email to verify your account.";
        } else {
            $error_message = "Error registering user. Please try again later.";
            // You might want to log the error for debugging purposes.
            error_log("MySQL error: " . mysqli_error($GLOBALS["db_host"]));
        }
    }
}
?>
