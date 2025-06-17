
  <form action="registration.php" method="post">
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

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Input (Important for security - sanitize and validate all data!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username (Basic example - adjust as needed)
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters long.";
    }

    // Validate email (More robust validation recommended)
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Validate password (Strong password requirements are highly recommended)
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }


    // Hash the password (VERY IMPORTANT - Never store passwords in plain text)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if there are any errors
    if (!empty($username_error) || !empty($email_error) || !empty($password_error)) {
        $errors = array(
            "username_error" => $username_error,
            "email_error" => $email_error,
            "password_error" => $password_error
        );
    } else {
        // Insert into the database
        try {
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL query
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $pdo->prepare($sql);

            // Bind the parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            // Execute the query
            $stmt->execute();
            echo "Registration successful! Please check your email to verify your account."; // Replace with a more informative message

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Handle database errors
        }
    }
}
?>
