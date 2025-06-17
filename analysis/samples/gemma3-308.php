
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Successful login
      // You can now do something with the user's information
      // For example, set a session variable
      session_start();
      $_SESSION['username'] = $username;
      $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column
      return true; // Indicate successful login
    } else {
      return false; // Indicate login failed (username or password incorrect)
    }
  } else {
    // Something went wrong with the query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage:
// To check if the login is successful:
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit(); // Important to stop further execution
  } else {
    // Login failed - Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
