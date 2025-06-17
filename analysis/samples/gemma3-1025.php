
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

<h2>User Login</h2>

<form method="post" action="">
    Username: <input type="text" name="username" placeholder="Enter username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values!)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Session handling
session_start();

// Function to handle user login
function loginUser($username, $password) {
  // Database connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Bind parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true; // Login successful
    } else {
      return false; // No user found or multiple users with the same username
    }

    $stmt->close();
  } else {
    // Error in query execution
    echo "Query execution failed: " . $conn->error;
    return false;
  }
  $conn->close();
}


// Example Usage (Demonstration -  Do NOT use this directly in production)
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Login failed - Display error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
