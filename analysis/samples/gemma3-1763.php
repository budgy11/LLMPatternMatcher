
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>

  <h1>Forgot Password</h1>

  <form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="newPassword">New Password:</label>
    <input type="password" id="newPassword" name="newPassword" required><br><br>

    <input type="submit" value="Reset Password">
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting the password reset.
 * @return bool True if password reset email was sent successfully, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Validate Email (basic check - customize as needed)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided."); // Log for debugging
        return false;
    }

    // 2. Check if the user exists
    $user = getUserByEmail($email);  // Implement this function (see below)

    if ($user === false) { // getUserByEmail should return false if user not found.
        error_log("User with email '$email' not found.");
        return false;
    }

    // 3. Generate a unique token and store it (using a hash)
    $token = generateUniqueToken();

    // 4.  Store the token in the database associated with the user
    $token_id = storeToken($user->id, $token);

    if ($token_id === false) {
        error_log("Failed to store token for user '$email'.");
        return false;
    }


    // 5.  Build the reset link
    $reset_link = "/reset_password.php?token=" . urlencode($token);

    // 6.  Send the reset email (implement this)
    if (!sendResetEmail($user->email, $reset_link)) {
        error_log("Failed to send reset email to '$email'.");
        //  Optionally, you might delete the token from the database if the email
        //  fails to send.  This prevents someone from stealing the token.
        deleteToken($token_id);
        return false;
    }


    return true;
}


/**
 *  Helper functions (you need to implement these)
 */

/**
 * Retrieves a user from the database based on email.  Replace with your actual database query.
 *
 * @param string $email The email address of the user to retrieve.
 * @return User|false The User object if found, or false if not found.
 */
function getUserByEmail(string $email)
{
    // **IMPORTANT:** Replace this with your actual database query to fetch user data
    // based on the email. This is just a placeholder.
    // Example (using a MySQL database):
    // $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    // $stmt->bind_param("s", $email);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // If the user is found:
    // if ($result->num_rows > 0) {
    //     $user = new User();
    //     $user->load($result->fetch_assoc());
    //     $stmt->close();
    //     return $user;
    // } else {
    //   $stmt->close();
    //   return false;
    // }

    // For demonstration purposes, return a fake user object if email is 'test@example.com'
    if ($email === 'test@example.com') {
      $user = new User();
      $user->id = 1;
      $user->email = $email;
      return $user;
    }

    return false;
}


/**
 * Generates a unique token.
 *  This should use a strong random string generator for security.
 *
 * @return string A unique token.
 */
function generateUniqueToken()
{
    return bin2hex(random_bytes(32)); // Generates a 32-byte random string
}


/**
 * Stores the token in the database for the given user ID.
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return int|false The ID of the token if stored successfully, or false if there was an error.
 */
function storeToken(int $userId, string $token)
{
    // **IMPORTANT:** Replace this with your actual database query to store the token.
    // Example (using a MySQL database):
    // $stmt = $db->prepare("INSERT INTO tokens (user_id, token, expiry_date) VALUES (?, ?, ?)");
    // $stmt->bind_param("iii", $userId, $token, date('Y-m-d H:i:s', time() + 3600)); // expiry in 1 hour
    // $result = $stmt->execute();

    // if ($result === false) {
    //     error_log("Error storing token: " . error_get_last()['message']);
    //     return false;
    // }

    // Get the last inserted ID (assuming auto-increment)
    // $last_id = $db->insert_id;
    // $stmt->close();
    // return $last_id;

    // Placeholder - returning a dummy token ID
    return mt_rand(1, 100000); // Just a dummy for demonstration
}


/**
 * Deletes a token from the database based on its ID.
 *
 * @param int $token_id The ID of the token to delete.
 * @return bool True if the token was deleted successfully, false otherwise.
 */
function deleteToken(int $token_id)
{
    // **IMPORTANT:** Replace this with your actual database query to delete the token.
    // Example (using a MySQL database):
    // $stmt = $db->prepare("DELETE FROM tokens WHERE id = ?");
    // $stmt->bind_param("i", $token_id);
    // $result = $stmt->execute();

    // if ($result === false) {
    //     error_log("Error deleting token: " . error_get_last()['message']);
    //     return false;
    // }
    // $stmt->close();
    // return true;

    // Placeholder - returning true
    return true;
}

/**
 * Sends a password reset email to the specified email address.
 *
 * @param string $email The email address to send the email to.
 * @param string $reset_link The password reset link.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function sendResetEmail(string $email, string $reset_link)
{
    // **IMPORTANT:** Implement your email sending logic here.
    // This is just a placeholder.

    //Example using a fake email sending
    error_log("Sending reset email to: " . $email . " with link: " . $reset_link);

    return true;  // Always returns true for demonstration purposes
}

//  --------------------- User Class (example) ---------------------
class User {
  public $id;
  public $email;

  public function load($data) {
    $this->id = $data['id'];
    $this->email = $data['email'];
  }
}
?>
