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

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Form handling (checking if the form was submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate the form data
    $errors = array();

    if (empty($username)) {
        $errors["username"] = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors["username"] = "Username must be at least 3 characters long.";
    }

    if (empty($email)) {
        $errors["email"] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors["password"] = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters long.";
    }

    if (empty($confirm_password)) {
        $errors["confirm_password"] = "Confirm Password cannot be empty.";
    } elseif ($password != $confirm_password) {
        $errors["confirm_password"] = "Passwords do not match.";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ss", $username, $hashed_password, $username);

        // Execute the query
        if ($stmt->execute()) {
            echo "Registration successful!  You have been redirected.";
            // Redirect to a success page or display a message
        } else {
            echo "Registration failed: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
