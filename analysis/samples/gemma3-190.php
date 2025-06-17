
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic example - improve for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (filter_var($username, FILTER_SANITIZE_STRING) === false) {
    $error = "Invalid username.  Please use letters and numbers only.";
  } else {
    // Hash the password (IMPORTANT - NEVER store passwords in plain text)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check the credentials
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Prepare the SQL query (use prepared statements to prevent SQL injection)
      $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

      // Execute the query with the username and hashed password
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $username, $hashed_password); // 'ss' indicates two string parameters
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        // User found - set session variables
        session_start();
        $_SESSION["user_id"] = $result->fetch_assoc()["user_id"]; // Assuming you have a user_id column
        $_SESSION["username"] = $username;
        $success = "Login successful!";
        $stmt->close();
        $conn->close();

        // Redirect the user (optional - depends on your application)
        header("Location: welcome.php"); // Redirect to a welcome page or your main application
        exit();
      } else {
        $error = "Invalid username or password.";
        $stmt->close();
        $conn->close();
      }
    }
  }
}
?>
