
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
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="logout.php">Logout</a> (This would be a separate file)

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle login
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

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // User found, you can now return user information
      $user_data = $result->fetch_assoc();
      //  You can now do something with the $user_data, like setting a session
      //  or redirecting the user to a different page.
      return $user_data;
    } else {
      return null; // User not found
    }

  } else {
    // Error executing the query
    return null;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (This part is for testing - it's not part of the login function)
// This is a sample form, you'd typically handle this in your HTML form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the login function
  $user = loginUser($username, $password);

  if ($user) {
    // User logged in successfully
    echo "Welcome, " . $user["username"] . "!";
    // Set a session to keep the user logged in (example)
    session_start();
    $_SESSION["user_id"] = $user["user_id"]; // Assuming user_id is in the user data
    // You could redirect the user to a secure area
    // header("Location: secure_area.php");
    // exit;

  } else {
    echo "Invalid username or password.";
  }
}

?>
