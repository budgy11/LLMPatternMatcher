    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Error handling function
function display_error($message) {
    echo "<div style='color: red;'>Error: " . $message . "</div>";
}


// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validation checks
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    // Email validation
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Confirm password validation
    if (empty($confirm_password)) {
        $errors[] = "Confirm password cannot be empty.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If there are any errors, display them
    if (!empty($errors)) {
        display_error(implode(", ", $errors));
    } else {
        // If no errors, proceed with registration

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Hash the password before inserting into the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "<div style='color: green;'>Registration successful!</div>";
            // Redirect to a success page or the homepage
            header("Location: /success.php"); // Replace with your success page URL
            exit();
        } else {
            // Error executing the query
            echo "<div style='color: red;'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

// Database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
