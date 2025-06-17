    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="password" id="new_password" name="new_password" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// For simplicity, let's assume $db is a mysqli connection object

// Function to handle the forgot password process
function forgotPassword($email) {
  // 1. Validate Email (Important for security)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists
  $result = mysqli_query($db, "SELECT id, username FROM users WHERE email = '$email'");
  if (mysqli_num_rows($result) == 0) {
    return "User not found.";
  }

  // 3. Generate a Unique Token
  $token = bin2hex(random_bytes(32));

  // 4. Update the User's Record with the Token
  mysqli_query($db, "UPDATE users SET password_reset_token = '$token', password_reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE email = '$email'");

  // 5. Send the Password Reset Email
  $to = $email;
  $subject = "Password Reset Request";
  $message = "Please use the following link to reset your password: " . $_SERVER['REQUEST_URI'] . 
              "<br><br>This link will expire in 1 hour.";
  $headers = "From: your_website@example.com"; // Replace with your actual email address

  mail($to, $message, $headers);

  return "Password reset email sent. Please check your inbox.";
}


// Example Usage (Illustrative -  This would be part of your form processing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_result = forgotPassword($email);
  echo $reset_result;
}

?>
