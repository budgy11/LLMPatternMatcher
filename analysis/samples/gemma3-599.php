    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Successful login
      session_start(); // Start the session to store user data

      // Set session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];

      // Redirect to a secure page (e.g., dashboard)
      header("Location: dashboard.php"); // Replace with your secure page
      exit(); 

    } else {
      // Login failed
      return false;
    }

  } else {
    // Login failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

}

// Example usage (from a login form - this is just an example, you'll need a form to submit the username and password)
// $username = $_POST['username'];
// $password = $_POST['password'];

// if (isset($username) && isset($password)) {
//   if (loginUser($username, $password)) {
//     echo "Login successful! <a href='dashboard.php'>Go to Dashboard</a>";
//   } else {
//     echo "Invalid username or password.";
//   }
// }


?>
