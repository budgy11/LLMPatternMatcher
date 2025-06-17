
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


function insertUser($username, $password) {
  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $query = "INSERT INTO users (username, password) VALUES (?, ?)";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  $stmt->bind_param("ss", $username, $hashed_password); // "ss" for string parameters

  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  $stmt->close();
  $conn->close();
}


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $password);

      // Execute the query
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      // Check if a user was found
      if ($result->num_rows == 1) {
        // User found, set session variables
        session_start();
        $user_id = $result->fetch_assoc()["id"];
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;

        // Redirect to a protected page
        header("Location: protected_page.php"); // Replace with your protected page
        exit(); // Important to stop further execution
      } else {
        $error_message = "Invalid username or password.";
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>
