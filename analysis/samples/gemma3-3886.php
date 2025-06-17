    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and Execute the SQL Query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Securely hash the password
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";  // Use prepared statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ssi", $username, $hashed_password, $email); // "ssi" indicates string, string, string

    if ($stmt->execute()) {
      // Registration successful
      $stmt->close();
      $conn->close();
      return true;
    } else {
      // Registration failed
      $error = $stmt->error;
      $stmt->close();
      $conn->close();
      return false;
    }
  } else {
    // Failed to prepare statement
    $stmt->close();
    $conn->close();
    return false;
  }
}

// Example Usage (Illustrative -  This will likely be handled by a form submission)
// Assume a form has submitted data:
// $username = $_POST["username"];
// $password = $_POST["password"];
// $email = $_POST["email"];

// if (empty($username) || empty($password) || empty($email)) {
//    echo "All fields are required.";
// } else {
//     if (registerUser($username, $password, $email)) {
//         echo "Registration successful!  Please check your email for verification instructions.";
//         // Send email verification (not implemented here - you'd need an email sending function)
//     } else {
//         echo "Registration failed. Please try again.";
//     }
// }

?>
