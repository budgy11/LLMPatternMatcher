  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Request Password Reset</button>
</form>


<?php

// Assuming you have a database connection established and stored in a variable like $db

// Function to handle the forgot password process
function forgotPassword($email) {
    // 1. Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if the user exists in the database
    $user = getUserByEmail($email);  //  Assuming you have a function getUserByEmail()

    if (!$user) {
        return "User not found.";
    }

    // 3. Generate a unique token
    $token = generateUniqueToken();

    // 4. Store the token and timestamp in the database for the user
    storeToken($user['id'], $token);

    // 5. Send the reset password email
    $subject = "Password Reset Request";
    $message = "Click this link to reset your password: " . base_url() . "/reset-password?token=" . $token; // Replace base_url() with your actual base URL
    $headers = "From: Your Website <noreply@yourwebsite.com>";

    mail($email, $subject, $message, $headers);

    return "Password reset link has been sent to your email address.";
}

// **Helper Functions (Implement these)**

// 1. Get user by email
function getUserByEmail($email) {
    // Replace this with your actual database query
    // This is just a placeholder.  Adapt to your database system and user table.
    $db = getDatabaseConnection();  // Assuming you have a function to get the DB connection
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}



// 2. Generate a unique token (e.g., using random characters)
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Generates a 64-character hexadecimal string
}

// 3. Store the token and timestamp in the database
function storeToken($userId, $token) {
  // Replace this with your database update query
  // This is just a placeholder. Adapt to your database system and table structure.
  $db = getDatabaseConnection();
  $sql = "INSERT INTO password_tokens (user_id, token, expiry_timestamp)
          VALUES ($userId, '$token', NOW() + INTERVAL 30 DAY)"; // 30 days expiry
  $result = $db->query($sql);

  if (!$result) {
    error_log("Database error storing token: " . $db->error); // Log the error
    return false;
  }
  return true;
}



// *******  Database Connection & Helper Functions (You need to implement these) *******

// Replace with your actual database connection logic
function getDatabaseConnection() {
    // Implement your database connection here
    // Example (MySQLi):
    $servername = "localhost";
    $username = "your_db_username";
    $password = "your_db_password";
    $dbname = "your_database_name";

    $db = new mysqli($servername, $username, $password, $dbname);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    return $db;
}


// *******  End of Helper Functions *******



// Example Usage (in your form or controller)
// $email = $_POST['email'];  // Get email from form input
// $result = forgotPassword($email);
// echo $result;

?>
