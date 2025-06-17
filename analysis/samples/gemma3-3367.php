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

// ... (Database connection code as before)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // ... (Validation code as before)

  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $hashed_password); // "ss" means two strings
  $stmt->execute();

  // ... (Rest of the registration logic)

  $stmt->close();
}


<?php

// Database configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate the form data
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
    } elseif ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if there are any errors
    if (count($errors) > 0) {
        // Display errors (for debugging - in production, use a more robust error handling)
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Hash the password - IMPORTANT for security!
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!  You have been logged in.";
            // Optionally, redirect to a welcome page or login page
            // header("Location: welcome.php");
            // exit();
        } else {
            // Error during registration
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }
}

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
