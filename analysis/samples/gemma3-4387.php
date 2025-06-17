    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Input Validation and Sanitization (VERY IMPORTANT!)
  // Sanitize inputs to prevent SQL injection and other security vulnerabilities
  $username = $conn->real_escape_string($username); // Escape for SQL
  $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
  $email = $conn->real_escape_string($email);

  // Add more validation here (e.g., email format, min/max lengths)

  // 3. SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

  // 4. Execute Query
  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 5. Close Connection
  $conn->close();
}

// Example Usage (Handle form submission - replace with your actual form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful! You have been redirected.";
    // Redirect the user to a success page or log them in.
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>
