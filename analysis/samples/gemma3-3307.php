    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the password - Important for security!
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // 4. Prepare the statement
  $stmt = $conn->prepare($sql);

  // 5. Bind the parameters
  $stmt->bind_param("ssi", $username, $hashed_password, $email);

  // 6. Execute the query
  if ($stmt->execute()) {
    return true; // Registration successful
  } else {
    // Handle errors
    echo "Error: " . $stmt->error;
    return false;
  }

  // 7. Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage (Handle form submission - replace with your actual form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get data from the form
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate the data (IMPORTANT - Add more robust validation here!)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required.";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      echo "Registration successful! Please check your email for verification.";
    } else {
      echo "Registration failed.";
    }
  }
}
?>
