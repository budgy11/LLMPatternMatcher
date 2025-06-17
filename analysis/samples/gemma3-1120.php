
</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the login process
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user with the given username exists
  if ($result->num_rows == 0) {
    return false; // User doesn't exist
  }

  $user = $result->fetch_assoc();
  $stored_password = $user['password'];

  // Verify the password
  if (password_verify($password, $stored_password)) {
    // Password is correct, set session variables
    session_start();
    $_SESSION['username'] = $username;
    return true; // Login successful
  } else {
    return false; // Password incorrect
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (handling login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    // Redirect to a secure page or display a success message
    header("Location: secure_page.php"); // Replace with your secure page
    exit();
  } else {
    // Display an error message
    $error = "Invalid username or password.";
  }
}
?>
