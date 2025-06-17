

<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_SANITIZE_STRING)) {
    $error = "Invalid username.  Please use alphanumeric characters.";
  } elseif (!filter_var($password, FILTER_SANITIZE_STRING)) {
    $error = "Invalid password.  Please use alphanumeric characters.";
  }

  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";

  // Bind parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);

  // Execute the query
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start();
      $user_row = $result->fetch_assoc();
      $_SESSION["user_id"] = $user_row["id"];
      $_SESSION["username"] = $user_row["username"];
      echo "Login successful!";
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();

    } else {
      echo "Invalid username or password.";
    }

  } else {
    echo "Query execution error: " . $conn->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

} else {
  // If the form hasn't been submitted, display the login form
  // You can include the HTML of your login form here
  echo "<form method='post' action=''>";
  echo "Username: <input type='text' name='username'>";
  echo "<br>";
  echo "Password: <input type='password' name='password'>";
  echo "<br>";
  echo "<input type='submit' value='Login'>";
  echo "</form>";
}

?>
