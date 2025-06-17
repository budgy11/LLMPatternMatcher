
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" minlength="8">

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validation - Basic checks
    if (empty($username) || empty($email) || empty($password)) {
        $errors = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors = "Username must be at least 3 characters long.";
    } elseif (preg_match("/@/", $email)) {
        $errors = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $errors = "Password must be at least 8 characters long.";
    } else {
        // Hash the password - IMPORTANT for security!
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!  You have been redirected.";
            // Redirect to a success page or login page
            header("Location: login.php");
            exit;
        } else {
            // Handle errors
            echo "Error: " . $stmt->error;
        }
    }
}
?>
