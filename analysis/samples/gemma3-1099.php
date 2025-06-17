    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Authentication successful!
      // Store the user's session information.  Important security measure.
      $_SESSION['user_id'] = $row['id']; // Assuming 'id' is the primary key of the users table
      $_SESSION['username'] = $username;
      return true;
    } else {
      return false;
    }
  } else {
    // Query error
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage:  Demonstration - Do not include in production code
// You would normally get this from a form submission.
//echo "Login Attempt";
//if (isset($_POST['username']) && isset($_POST['password'])) {
//  $username = $_POST['username'];
//  $password = $_POST['password'];
//  if (loginUser($username, $password)) {
//    echo "Login successful!  Redirecting...";
//    header("Location: welcome.php"); // Redirect to a welcome page
//    exit;
//  } else {
//    echo "Invalid username or password.";
//  }
//}
//else {
//   echo "Login Form incomplete.";
//}


//Example of user creation - Uncomment for testing purposes.
/*
function createUser($username, $password, $email){
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("sss", $username, $password, $email);

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
*/



?>
