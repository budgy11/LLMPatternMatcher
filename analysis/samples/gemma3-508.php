
    <form method="post" action="">
        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <input type="submit" value="Login" />
    </form>

    <br>
    <a href="register.php">Don't have an account? Register here!</a>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare and Execute the SQL Query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  if ($stmt->execute() === false) {
      echo "Query execution failed: " . $conn->error;
      $stmt->close();
      $conn->close();
      return false;
  }

  // 3. Get the Result
  $result = $stmt->get_result();

  // 4. Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found, process the user
    $row = $result->fetch_assoc();
    // You might want to do more than just return true here.
    // For example, you could set a session variable or redirect the user.
    // For simplicity, let's just return true.
    $stmt->close();
    $conn->close();
    return true;
  } else {
    // User not found
    $stmt->close();
    $conn->close();
    return false;
  }
}

// Example Usage:  (For testing - remove or comment out in a production environment)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!";
    // Redirect the user, set a session, etc.
  } else {
    echo "Invalid username or password.";
  }
}
?>
