
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="reset.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="">

    <input type="submit" value="Request Password Reset">
  </form>

  <hr>

  <form method="post" action="reset.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="hidden" name="reset_token" value="">

    <label for="reset_token">Reset Token:</label>
    <input type="text" id="reset_token" name="reset_token" required><br><br>

    <input type="submit" value="Reset Password">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a user table with an 'email' column.
// Replace these with your actual database connection details and table name.

// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database_name';

// Table name
$usersTable = 'users';

// Function to reset password
function forgotPassword($email, $host, $username, $password, $database, $usersTable) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }

    // 2. Check if the user exists
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    $sql = "SELECT id, email FROM $usersTable WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $conn->close();

        // 3. Generate a unique token/code for password reset
        $resetToken = bin2hex(random_bytes(32)); 

        // 4. Store the token in the database
        $sql = "UPDATE $usersTable SET reset_token = '$resetToken' WHERE id = $userId";
        if ($conn = new mysqli($host, $username, $password, $database)) {
            if ($conn->query($sql) === TRUE) {
                $conn->close();
                return $resetToken; // Return the token for the user to use.
            } else {
                $conn->close();
                return "Error updating token: " . $conn->error;
            }
        } else {
            return "Connection failed: " . $conn->connect_error;
        }
    } else {
        return 'User not found.';
    }
}



// Example usage (for demonstration - this would usually be part of a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if ($email) {
        $resetToken = forgotPassword($email, $host, $username, $password, $database, $usersTable);
        if (strpos($resetToken, "Error") !== false) { // Check for error messages
            echo "<p style='color:red'>" . $resetToken . "</p>";
        } else {
            echo "<p>A password reset link has been sent to your email address.</p>";
            echo "<p>Please check your inbox (and spam folder) for a link to reset your password.</p>";
            echo "<p>The token is: " . $resetToken . "</p>"; //Display token if you want to show it to the user.
        }

    } else {
        echo "<p style='color:red'>Please enter your email address.</p>";
    }
}

?>
