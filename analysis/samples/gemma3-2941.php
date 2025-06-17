    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Configuration (Replace with your database credentials)
$dbHost = 'localhost';
$dbName = 'your_database_name';
$dbUser = 'your_username';
$dbPass = 'your_password';

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Validate Inputs (Crucial for security)
  $username = trim($username);  // Remove whitespace
  $password = trim($password);
  $email = trim($email);

  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  // Basic password validation (you should use a stronger hashing method in production)
  if (strlen($password) < 8) {
    return "Error: Password must be at least 8 characters long.";
  }


  // 2. Database Connection
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  if ($conn->connect_error) {
    return "Error: Connection failed: " . $conn->connect_error;
  }

  // 3. Prepare and Execute SQL Query
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashedPassword', '$email')";

  if ($conn->query($sql) === TRUE) {
    return "Registration successful! You have been logged in.";
  } else {
    return "Error: " . $sql . "<br>Error: " . $conn->error;
  }
}


// Example Usage (This is just for demonstration - you would use this from a form submission)
//  $username = $_POST['username'];
//  $password = $_POST['password'];
//  $email = $_POST['email'];
//
//  $registrationResult = registerUser($username, $password, $email);
//
//  echo $registrationResult;


?>
