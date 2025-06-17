
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);

  // Execute the statement
  if ($stmt->execute() === false) {
    die("Error executing query: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return false; // User not found
  }

  $user = $result->fetch_assoc();

  // Verify the password
  if (password_verify($password, $user['password'])) {
    // Password is correct
    $stmt->close();
    return $user; // Return user data
  } else {
    // Password is incorrect
    $stmt->close();
    return false; // Return false to indicate incorrect password
  }
}

// Example usage (Handle login form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $user = loginUser($username, $password);

  if ($user) {
    // Login successful
    session_start();
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    echo "Login successful!  Welcome, " . $user["username"];
    // Redirect to a protected page or display content
    header("Location: /protected_page.php"); // Change this to your desired URL
    exit();
  } else {
    // Login failed
    echo "Invalid username or password.";
  }
}

// Form for login
?>
