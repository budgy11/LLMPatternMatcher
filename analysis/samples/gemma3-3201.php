    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2.  Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ssi", $username, $password, $email);  // "ssi" indicates string, string, string

  // 3. Execute the query
  if ($stmt->execute()) {
    // User registration successful
    return true;
  } else {
    // Registration failed
    return false;
  }

  // 4. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example usage (This part would typically be in a form submission handler)
// Assuming the form data is in $username, $password, and $email
//  (e.g., from an HTML form submission)

//  Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (Crucial for security - see notes below)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required.";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      echo "Registration successful! You have been redirected.";
      // Redirect the user (optional)
      header("Location: login.php");  // Or your desired login page
      exit;
    } else {
      echo "Registration failed. Please try again.";
    }
  }
}

?>
