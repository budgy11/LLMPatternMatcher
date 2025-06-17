    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling (if submitting the registration form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate the email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate the password (e.g., minimum length)
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check if username is already taken (basic check - improve this in a real application)
    if (!empty($username)) {
        // You would typically query the database here to check for username existence
        // This is a placeholder for a database query.  Don't skip this step in production!
        $username_exists = false; 
        // In a real application, you would perform a database query here.
        // For this example, we'll just assume the username is valid.

    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password (VERY IMPORTANT!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful!  You have been redirected.";
            // Redirect (or display a success message)
            header("Location: login.php"); // Or whatever your login page is
            exit;
        } else {
            echo "Registration failed: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
