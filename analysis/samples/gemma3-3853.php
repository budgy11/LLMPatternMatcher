    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data (Important security step)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username (basic example - you should have more robust validation)
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters.";
    }

    // Validate email
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate password (basic example - stronger password requirements are recommended)
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters.";
    }

    // If there are validation errors, display them
    if ($username_error || $email_error || $password_error) {
        $errors = array(
            "username_error" => $username_error,
            "email_error" => $email_error,
            "password_error" => $password_error
        );
        // Redirect back to the registration form with errors
        header("Location: registration.php?errors=" . urlencode($errors));
        exit();
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    // Execute the query
    $result = mysqli_query($GLOBALS["servername"], $sql);

    // Check if the query was successful
    if ($result) {
        // Redirect to a success page or the homepage
        header("Location: success.php");
        exit();
    } else {
        // Handle the error - important for debugging
        echo "Error: " . mysqli_error($GLOBALS["servername"]);
    }
} else {
    // If the form hasn't been submitted, display the registration form
    $errors = array(); // Initialize empty array for errors.
}

?>
