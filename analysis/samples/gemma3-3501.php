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
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];


    // Validate input (Important for security - more robust validation is recommended)
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (empty($confirm_password)) {
        $errors[] = "Confirm Password cannot be empty.";
    }
    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    // Add more validation as needed (e.g., username length, password strength)


    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password (VERY IMPORTANT - don't store passwords in plain text)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please verify your email.";
            // Optionally: Redirect to a login page or another page
        } else {
            // Registration failed
            echo "Registration failed: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display errors
        echo "Errors:<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
    }
} else {
    // If the form hasn't been submitted, display the registration form
    ?>
