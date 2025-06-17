

<?php

// Assuming you have a database connection established 
// and you have a 'users' table with an 'email' and 'password' column.

// 1. Function to Reset Password
function forgotPassword($email, $new_password) {
  // **Security Considerations:**
  // - Input validation is absolutely crucial.  Never trust user input!
  // - Use prepared statements with parameterized queries to prevent SQL injection.
  // - Hash the new password before storing it in the database.

  // **Input Validation (CRITICAL)**
  if (empty($email)) {
    return "Invalid email address.";
  }

  if (empty($new_password)) {
    return "Password cannot be empty.";
  }

  // **Password Hashing (IMPORTANT)**
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);  // Use a strong hashing algorithm.

  // **Database Query (Prepared Statement - SQL Injection Prevention)**
  try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Password reset successfully.  Please check your email for verification (if you set up email verification).";
    } else {
      return "User not found with this email address.";
    }
  } catch (PDOException $e) {
    return "An error occurred while resetting your password: " . $e->getMessage();
  }
}

// 2. Example Usage (Illustrative - Not a full form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  $reset_message = forgotPassword($email, $new_password);
  echo $reset_message;
}
?>
