        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password">
        <br><br>

        <button type="submit">Login</button>
    </form>

    <br><br>
    <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database credentials - REPLACE WITH YOUR ACTUAL CREDENTIALS
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
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, get user data
      $user = $result->fetch_assoc();

      // Session management - setting user ID and other data
      $_SESSION["user_id"] = $user["id"];  // Store user ID in session
      $_SESSION["username"] = $user["username"];
      // You can store more user data in the session as needed, e.g., roles, name, etc.

      return true; // Login successful
    } else {
      return false; // User not found or incorrect password
    }

  } else {
    return false; // Error executing the query
  }

  // Close the statement
  $stmt->close();

  // Close the connection
  $conn->close();
}


// Example usage:  This is for demonstration -  DO NOT put actual login form logic here.
//  This is just a simplified example.  You'll need a form and proper handling.
if (isset($_POST["username"]) && isset($_POST["password"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  You are now logged in.";
    // Redirect to a protected page or display a welcome message
    // header("Location: protected_page.php");
    // exit;

  } else {
    echo "Invalid username or password.";
  }
}
?>
