

<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column.

// 1.  Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 2. Get the email from the form
  $email = $_POST["email"];

  // 3. Validate the email format (basic validation - enhance this!)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // 4.  Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?"); // Use prepared statement
    $stmt->bind_param("s", $email); // 's' indicates a string
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      $error = "User with this email does not exist.";
    } else {
      // 5. Generate a temporary password (you can use a more secure method)
      $temporary_password = "temp_password_" . md5($email); // For demonstration, using MD5 - NOT recommended for production.

      // 6. Prepare the update statement
      $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
      $update_stmt->bind_param("ss", $temporary_password, $email);  // 's' indicates a string

      // 7. Execute the update
      if ($update_stmt->execute()) {
        $success = "Password reset email has been sent to your email address.";
        //  Send an email with the temporary password.  (See below for email sending)
      } else {
        $error = "Error resetting password.  Please try again.";
      }

      // 8. Close the statements
      $stmt->close();
      $update_stmt->close();
    }
  }
}
?>
