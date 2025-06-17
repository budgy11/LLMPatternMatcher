        Username: <input type="text" name="username" />
        Password: <input type="password" name="password" />
        <br />
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    if ($stmt->execute()) {
      // Get the result
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Session management -  Store user data in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true; // Login successful
      } else {
        return false; // No user found with those credentials
      }
    } else {
      return false; // Error executing the statement
    }

    $stmt->close();
  } else {
    return false; //  Error preparing the statement
  }

  $conn->close();
}


// Example Usage (Illustrative - This would usually be in a form submission handler)
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate inputs (Important for security - add more thorough validation)
  if (empty($username) || empty($password)) {
    echo "Username and password cannot be empty.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      echo "Login successful!  You are now logged in.";
      // Redirect to a secure page or display a welcome message
      header("Location: secure_page.php"); // Replace with your secure page
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}
?>
