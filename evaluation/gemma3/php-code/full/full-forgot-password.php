<?php

$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "ecommerce_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If you need to perform database operations here, do it from this file.

?>
<?php

// Include the database connection file
require_once 'database.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Validate email (basic validation - improve in a real app)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Prepare the SQL query (using prepared statements - crucial for security)
        $query = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $email); // "s" indicates a string parameter
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                $userId = $user['id'];
                $hashedPassword = $user['password']; // Assume password is already hashed

                // Set a temporary password (stronger passwords should be generated)
                $tempPassword = 'temp_password_123';

                // Update the user's password (temporarily)
                $query = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($query);

                if ($stmt) {
                    $stmt->bind_param("ss", $tempPassword, $userId);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        // Send an email with the temporary password
                        // (Implement email sending logic here - e.g., using PHPMailer)
                        // For example:
                        // $to = $email;
                        // $subject = 'Temporary Password for E-commerce';
                        // $message = 'Your temporary password is: ' . $tempPassword .
                        //             '<br><br>Please update your password immediately.';
                        // $headers = "From: ecommerce@example.com";
                        // mail($to, $subject, $message, $headers);

                        $success = "Password reset email sent.  Check your email.";
                    } else {
                        $error = "Error resetting password.";
                    }
                } else {
                    $error = "Error preparing statement.";
                }
            } else {
                $error = "No user found with this email address.";
            }
        } else {
            $error = "Error preparing statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

<h1>Forgot Password</h1>

<?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>

<?php if (isset($success)) { ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php } ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="Your Email">
    <button type="submit">Reset Password</button>
</form>

</body>
</html>
