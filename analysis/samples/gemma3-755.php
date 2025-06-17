
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
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
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Bind parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); // 'ss' means two strings

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Successful login - set session variables
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];

      // Redirect the user to a protected page
      header("Location: protected_page.php"); // Replace with your protected page URL
      exit();  // Important to stop further script execution
    } else {
      // Incorrect credentials
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

// Handle login request (e.g., from a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic example - add more robust validation)
  if (empty($username) || empty($password)) {
    echo "<p style='color:red;'>Username and password cannot be empty.</p>";
  } else {
    // Call the login function
    if (loginUser($username, $password)) {
      // Login successful -  you can redirect here if needed.
    } else {
      // Login failed - show an error message
      echo "<p style='color:red;'>Invalid username or password.</p>";
    }
  }
}

?>
