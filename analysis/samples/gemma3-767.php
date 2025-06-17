    Username: <input type="text" name="username" <?php if (isset($error_message) && $error_message == "Username and password cannot be empty.") { echo "required"; } ?>><br><br>
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
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";  // Use prepared statements for security

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      //  Store user data (e.g., session variables)
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];

      // You might redirect the user here:
      // header("Location: welcome.php");
      // exit();

      return true; // Login successful
    } else {
      return false; // User not found
    }

  } else {
    // Handle query errors
    echo "Query error: " . $conn->error;
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}



// --- Example Usage (Demonstration) ---

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security) -  Add more validation as needed
  if (empty($username) || empty($password)) {
    echo "<p style='color: red;'>Username and password cannot be empty.</p>";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      echo "<p style='color: green;'>Login successful!</p>";
      // Redirect to a welcome page or display a welcome message
      // header("Location: welcome.php");
      // exit();
    } else {
      echo "<p style='color: red;'>Invalid username or password.</p>";
    }
  }
}


?>
