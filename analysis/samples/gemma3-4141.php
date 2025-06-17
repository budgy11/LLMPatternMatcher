    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" minlength="8">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare and execute the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ssi", $username, $hashed_password, $email);  // "ssi" indicates string, string, string
    $stmt->execute();
    return true; // Registration successful
  } else {
    return false; // Error preparing or executing the query
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (Illustrative - Implement form handling in a real application)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Assuming form data is submitted with username, password, and email
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate data (Add more robust validation here - e.g., email format, password strength)
  if (empty($username) || empty($password) || empty($email)) {
    echo "All fields are required!";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      echo "Registration successful! You have been redirected.";
      // Redirect to a welcome page or login page
    } else {
      echo "Registration failed. Please try again.";
    }
  }
}
?>
