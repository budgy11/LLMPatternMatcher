    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your email address" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Replace this with your database connection details
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Check if the reset password request is valid
if (isset($_POST['email']) && isset($_POST['reset_token'])) {
    // 1. Validate Email
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // 2. Retrieve Token and User from Database
    $stmt = $conn->prepare("SELECT user_id, password_reset_token FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' indicates a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $user_id = $user_data['user_id'];
        $reset_token = $user_data['password_reset_token'];

        // 3. Verify Token
        if (verify_token($reset_token, $reset_token)) { //  use the verify_token function (defined below)

            // 4. Generate a New Password (or force user to set one)
            //  In a real application, you'd likely:
            //    a)  Provide a form for the user to set a new password.
            //    b)  Hash the new password securely.
            //    c)  Update the password in the database.

            //  For this example, we'll just output a message and a link to set a new password.

            echo "<p>Reset password link is valid.  Please set a new password.</p>";
            echo "<a href='reset_password.php?user_id=$user_id&reset_token=$reset_token'>Set New Password</a>";

        } else {
            echo "<p>Reset token is invalid.</p>";
        }

    } else {
        echo "<p>User not found with this email address.</p>";
    }

} else {
    echo "Invalid request.";
}


// --------------------------------------------------------------------
// Helper function to verify the token.
//  This is a placeholder.  You should implement a secure token verification.
//  This simple example just compares the token with itself which is insecure!
// --------------------------------------------------------------------
function verify_token($token, $stored_token) {
    return $token === $stored_token;
}


?>
