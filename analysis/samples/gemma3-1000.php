        Username: <input type="text" name="username" />
        <br><br>
        Password: <input type="password" name="password" />
        <br><br>
        <button type="submit">Register</button>
    </form>

    <br><br>
    <a href="login.php">Already have an account? Login here.</a>
</body>
</html>


<?php

// Database credentials - Replace with your actual values
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
  $sql = "SELECT id, username, password FROM users WHERE username = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameter
  $stmt->bind_param("s", $username);

  // Execute the statement
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Login successful
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        return true;
      } else {
        // Password mismatch
        return false;
      }
    } else {
      // User not found
      return false;
    }
  } else {
    // Error executing the query
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Demonstration -  This is for testing and should be replaced with your form input)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a protected page
    header("Location: /protected_page.php"); // Replace with your protected page
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>
