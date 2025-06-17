

<?php

// Database configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the login process
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the user's password hash
  $sql = "SELECT password_hash, salt FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $password_hash = $row['password_hash'];
    $salt = $row['salt'];

    // Verify the password
    if (password_verify($password, $password_hash . $salt)) {
      // Password is correct, set session variables
      $_SESSION['username'] = $username;
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}


// Example usage (handling the login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Successful login
    echo "Login successful!  You are now logged in as " . $username;

    // Redirect to a secure page or display a welcome message
    header("Location: secure_page.php"); // Replace secure_page.php with your secure page
    exit();
  } else {
    // Failed login
    echo "Invalid username or password.";
  }
}

?>
