    <button type="submit">Login</button>
  </form>

  <p>New user? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

  if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // User found, set session variables
      $_SESSION['username'] = $row['username'];
      $_SESSION['user_id'] = $row['id']; // Assuming you have a user_id column
      return true; // Login successful
    } else {
      // No user found or multiple users with the same username
      return false;
    }
  } else {
    // Query execution failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (for demonstration - this should be in a form submission)
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   $username = $_POST["username"];
//   $password = $_POST["password"];

//   if (loginUser($username, $password)) {
//     header("Location: user_dashboard.php"); // Redirect to a success page
//     exit();
//   } else {
//     echo "Invalid username or password.";
//   }
// }

?>
