
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

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

  // Prepare the statement
  $stmt = $conn->prepare($query);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // User found, you can now do something with the user data
      // Example:  Set a session variable
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true;
    } else {
      return false; // User not found
    }
  } else {
    // Error executing the query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage:  (This would typically come from a form submission)
// $username = $_POST['username'];
// $password = $_POST['password'];

// if (loginUser($username, $password)) {
//   echo "Login successful!";
//   // Redirect to a protected page or take other actions
// } else {
//   echo "Invalid username or password.";
// }


?>
