    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";  // Use prepared statement for security

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the statement
  if ($stmt->execute() === TRUE) {
    // Get the result
    $result = $stmt->get_result();

    // Check if any user was found
    if ($result->num_rows > 0) {
      // User found, store the data in a session variable
      $row = $result->fetch_assoc();
      $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column
      $_SESSION['username'] = $row['username'];

      return true; // Login successful
    } else {
      return false; // No user found
    }
  } else {
    return false; // Error executing the query
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage:  (This is just an example - you'll need to integrate this into your form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful! You are now logged in.";
    // Redirect to a protected page or display a welcome message
    header("Location: protected_page.php"); // Redirect to a protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
