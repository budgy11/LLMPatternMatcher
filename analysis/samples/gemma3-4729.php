    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $email, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the SQL query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password for security

  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();
    return true;
  } else {
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (you'll likely get this from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (important - see note below)
  if (empty($username) || empty($email) || empty($password)) {
    echo "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
  } else {
    // Register the user
    if (registerUser($username, $email, $password)) {
      echo "Registration successful!  You've been redirected.";
      // Redirect to a success page or login page
    } else {
      echo "Registration failed.";
    }
  }
}
?>
