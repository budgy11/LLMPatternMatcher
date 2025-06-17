
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the user's password hash
  $query = "SELECT password_hash, user_id FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username); // 's' indicates a string parameter
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // User not found
    return false;
  } else {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password_hash'];
    $user_id = $row['user_id'];
    $stmt->close();

    // Verify the password
    if (password_verify($password, $hashed_password)) {
      // Password is correct
      // You can store the user_id in a session or cookie for later use
      $_SESSION['user_id'] = $user_id;  // Store user ID in session
      return true;
    } else {
      // Password is incorrect
      return false;
    }
  }
}


// Example Usage (for demonstration - you'll integrate this into your form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    // Redirect to your protected page or dashboard
    header("Location: /protected_page.php"); // Replace with the URL of your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>
