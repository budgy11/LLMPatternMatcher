
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
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?;";  // Use parameterized query for security

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind the parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc(); // Fetch the user data as an associative array
      return $user; // Return the user data if login is successful
    } else {
      return null; // Return null if login fails
    }

    // Close the statement
    $stmt->close();
  } else {
    // Handle the error in preparing the statement
    error_log("Error preparing statement: " . $conn->error); // Log the error for debugging
    return null;
  }
}

// Example Usage (Demonstration - Don't use this in a live application without proper input validation and sanitization!)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT -  This is a simplified example)
  if (empty($username) || empty($password)) {
    echo "Username and Password cannot be empty.";
  } else {
    $user = loginUser($username, $password);

    if ($user) {
      // Login successful
      session_start();  // Start the session
      $_SESSION["username"] = $user["username"];  // Store username in session
      echo "Login successful!  You are logged in as " . $user["username"];
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}
?>
