
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" value=""><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values!)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];


    // Validate the data
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors["username"] = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors["username"] = "Username must be at least 3 characters long.";
    }

    // Email validation
    if (empty($email)) {
        $errors["email"] = "Email cannot be empty.";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format.";
        }
    }


    // Password validation
    if (empty($password)) {
        $errors["password"] = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters long.";
    }

    if (empty($confirm_password)) {
        $errors["confirm_password"] = "Confirm password cannot be empty.";
    } elseif ($password != $confirm_password) {
        $errors["confirm_password"] = "Passwords do not match.";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {

        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hash the password (Important for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email for verification instructions.";
        } else {
            // Registration failed
            echo "Registration failed: " . $conn->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
