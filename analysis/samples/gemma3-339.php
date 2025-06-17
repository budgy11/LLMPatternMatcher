
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <form method="post" action="">
        Username: <input type="text" name="username">
        Password: <input type="password" name="password">
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ?;";

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);  // "s" indicates a string parameter

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $hashed_password = $row['password']; // Retrieve the hashed password

      // Verify the password (Crucially, compare with the *hashed* password)
      if (password_verify($password, $hashed_password)) {
        // Login successful
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        return true;
      } else {
        // Incorrect password
        return false;
      }
    } else {
      // No user found with that username
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

// Example usage (simplified - you'll likely want to have a form and handle the form submission)
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a protected page or display a welcome message.
    header("Location: protected_page.php"); // Redirect to a different page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
