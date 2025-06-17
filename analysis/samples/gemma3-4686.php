
        <br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare the SQL statement
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // 4. Prepare the statement
  $stmt = $conn->prepare($sql);

  // 5. Bind the parameters
  $stmt->bind_param("ss", $username, $hashed_password, $email);

  // 6. Execute the statement
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


// Example Usage (This would typically come from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (Important for security and data integrity)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required!";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format!";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      echo "Registration successful! You can now log in.";
    } else {
      echo "Registration failed. Please try again.";
    }
  }
}

?>
