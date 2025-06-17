    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and validate the input
  $username = $conn->real_escape_string($username); // Escape SQL special characters
  $password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password for security
  $email = $conn->real_escape_string($email);


  // 3. Insert the user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

  if ($conn->query($sql) === TRUE) {
    return true; // Registration successful
  } else {
    return false; // Registration failed
  }

  // 4. Close the database connection
  $conn->close();
}



// Example usage (This is just for demonstration - you'd usually handle this in your form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful! Please check your email for verification instructions.";
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>
