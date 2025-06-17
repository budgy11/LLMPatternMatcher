    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the password - VERY IMPORTANT for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare and execute the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssi", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    // User registered successfully
    $stmt->close();
    $conn->close();
    return true;
  } else {
    // Error during registration
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    return false;
  }
}


// Example usage (This will be handled by the form submission)
//  You will need to create a form (HTML) to collect the user data.
//  The following part is just for demonstration - typically this would be done via POST.
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "User registered successfully! You have been redirected.";
  } else {
    echo "Registration failed. Please try again.";
  }
}
*/


//  To use this, you'll need:
//  1. Create a database table named "users" with columns:
//      - id (INT, PRIMARY KEY, AUTO_INCREMENT)
//      - username (VARCHAR)
//      - password (VARCHAR)
//      - email (VARCHAR)
//  2. Create an HTML form to collect the username, password, and email.
//  3. Submit the form data to this PHP file using POST.
//  4. The PHP code will then register the user.


?>
