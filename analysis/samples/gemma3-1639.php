
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    <form method="get" action="your_script_name.php"> <!-- Replace with the actual filename -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>


<?php

// Ensure this file is protected to prevent unauthorized access
// You should also store your database credentials securely,
//  e.g., in environment variables.  This example uses hardcoded credentials
//  for simplicity and demonstration.  NEVER do this in a production environment.

// Database connection details (replace with your actual details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// 1. Get the email from the form submission

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Basic email validation (you can add more sophisticated validation)
    if (empty($email)) {
        $error_message = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // 2. Generate a unique token
        $token = bin2hex(random_bytes(32)); // Generate a random, secure token

        // 3.  Hash the token for security
        $hashed_token = hash('sha256', $token);

        // 4.  Store the token and email in the database
        //  This part assumes you have a table named 'users' with columns:
        //   - email (VARCHAR)
        //   - password_hash (VARCHAR)  // Use password_hash() function to store hashed passwords
        //   - reset_token (VARCHAR)
        try {
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                // User exists, update the user record with the token
                $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = NOW() WHERE email = ? AND reset_token = ''");
                $stmt->execute([$token, $email]);

                if ($stmt->rowCount() > 0) {
                    // Redirect the user to a password reset page with the token
                    header("Location: reset_password.php?email=$email&token=$token");
                    exit(); // Stop further execution
                } else {
                    $error_message = "Error updating the token in the database.";
                }
            } else {
                // User does not exist
                $error_message = "User with this email address not found.";
            }

        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}
?>
