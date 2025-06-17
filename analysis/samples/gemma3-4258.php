    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Confirm Password: <input type="password" name="confirm_password" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters long.";
    }

    // Validate email
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }


    // Hash the password - VERY IMPORTANT for security!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if validation errors exist
    if ($username_error || $email_error || $password_error) {
        $errors = array(
            "username_error" => $username_error,
            "email_error" => $email_error,
            "password_error" => $password_error
        );
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!  Please check your email to verify your account.";
            // Optionally, redirect to a success page or show a confirmation message
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>
