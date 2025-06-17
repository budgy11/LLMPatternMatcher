    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation checks
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }


    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Sanitize the input (important for security)
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);


        // Hash the password (use a strong hashing algorithm like password_hash)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $name, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!  Please check your email to activate your account.";
            // Redirect to a welcome page or log the user in.
        } else {
            // Registration failed
            echo "Registration failed: " . $conn->error;
        }
    } else {
        // Display the form with error messages
        echo "<h2>Error:</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
    }
}
?>
