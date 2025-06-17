    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and Validate Inputs (VERY IMPORTANT!)
  $username = $conn->real_escape_string($username);
  $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
  $email = $conn->real_escape_string($email);

  // Basic validation (add more robust validation as needed)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Return false if any field is empty
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Return false if email is not a valid email format
  }

  // 3. Insert the user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Return true if insertion was successful
  } else {
    return false; // Return false if insertion failed
  }

  // 4. Close the database connection
  $conn->close();
}


// Example Usage (This is for demonstration and testing, typically done on a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful!  Please check your email to activate your account.";
  } else {
    echo "Registration failed. Please try again.";
  }
}

?>
