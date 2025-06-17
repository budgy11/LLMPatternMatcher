
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials!
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ?";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter

  // Execute the query
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Successful login
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        return true;
      } else {
        // Incorrect password
        return false;
      }
    } else {
      // User not found
      return false;
    }
  } else {
    // Query error
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage:

// 1. Form submission (in your HTML form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful, redirect to your welcome page or desired location
    header("Location: welcome.php"); // Replace with your welcome page
    exit();
  } else {
    // Login failed, display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
