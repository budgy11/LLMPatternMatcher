
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

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

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    die("Query preparation failed");
  }

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the query
  if (!$stmt->execute()) {
    die("Query execution failed: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Authentication successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Redirect the user
    header("Location: user_dashboard.php"); // Replace with your desired destination
    exit();
  } else {
    // Authentication failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (from a login form):
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   $username = $_POST["username"];
//   $password = $_POST["password"];
//   loginUser($username, $password);
// }

?>
