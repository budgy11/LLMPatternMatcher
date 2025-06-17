
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to handle login
function login($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and execute the query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";  // Use prepared statements for security
  $stmt = $conn->prepare($query);

  if ($stmt) {
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings
    $stmt->execute();
    $result = $stmt->get_result();

    // 3. Process the result
    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Set session variables (Important!)
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      return true;  // Login successful
    } else {
      return false; // Login failed
    }
  } else {
    return false; // Failed to prepare the statement
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (This part demonstrates how to call the function)
// To actually use this, you'll need a form to submit the username and password.

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (login($username, $password)) {
    // User logged in successfully
    header("Location: welcome.php"); // Redirect to a welcome page
    exit(); // Stop further execution
  } else {
    // Login failed
    $error = "Invalid username or password.";
    // You could display the error message here, for example, in a form error message.
    // For example:
    // echo "<p style='color:red;'>$error</p>";
  }
}
?>
