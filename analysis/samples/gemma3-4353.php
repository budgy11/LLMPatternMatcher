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

// Assuming you have a database connection established (e.g., mysqli)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }


// 1. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize Input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate Input (Basic)
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }


    // If no errors, proceed with registration
    if (empty($errors)) {
        // 2. Hash the Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Prepare SQL Query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);  // Use prepared statements for security
        if ($stmt) {
            $stmt->bind_param("sss", $username, $email, $hashed_password); // 's' for string

            // 4. Execute Query
            if ($stmt->execute()) {
                // Registration successful
                echo "Registration successful!  You have been logged in.";
                // Redirect to a welcome page or login page
                header("Location: /welcome.php"); // Replace with your welcome page URL
                exit();
            } else {
                // Handle query execution errors
                echo "Query failed: " . $db->error;
            }

            $stmt->close();
        } else {
            echo "Prepare statement failed: " . $db->error;
        }

    }
}

?>
