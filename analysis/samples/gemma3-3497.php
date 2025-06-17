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

// Include the database connection file (assuming it's named db_connect.php)
require_once 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate the input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }

    // Validate password strength (basic example)
    $password_length = strlen($password);
    if ($password_length < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) > 0) {
            $username_error = "Username already exists.";
        }
    } else {
        $error = mysqli_error($connection); // Handle database errors
    }


    // Insert the user into the database
    if (!empty($username_error) || !empty($email_error) || !empty($password_error)) {
        // Display error messages
        $error = $username_error ? $username_error : $email_error ? $email_error : $password_error;
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if (mysqli_query($connection, $sql)) {
            // Success!  Redirect to a welcome page or login page
            header("Location: welcome.php"); // Replace with your welcome page
            exit();
        } else {
            // Handle database errors
            $error = "Error: " . mysqli_error($connection);
        }
    }
}
?>
