    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// ... (Database connection and setup - as before) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // ... (Email validation - as before) ...

  if (!empty($result)) {
    $email = $result->email;
    $hashed_password = $result->password; // get the hashed password

    // Generate a new temporary password using password_hash
    $temporary_password = password_hash('temp_password_' . md5($email), PASSWORD_DEFAULT);

    // Update the password
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update_stmt->bind_param("ss", $temporary_password, $email);

    if ($update_stmt->execute()) {
      $success = "Password reset email has been sent to your email address.";
    } else {
      $error = "Error resetting password.  Please try again.";
    }
  }
}

?>
